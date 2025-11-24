<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Shipments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Product;

class TransactionController extends Controller
{
    private function applyStatus($trx)
    {
        if (! $trx) return $trx;
        try {
            $trx->status_text  = transactionStatusText($trx->tst_status);
            $trx->status_color = transactionStatusColor($trx->tst_status);
        } catch (\Throwable $e) {
            $statusMap = [
                1 => 'Menunggu Pembayaran',
                2 => 'Pembayaran Berhasil',
                3 => 'Menunggu Konfirmasi Penjual',
                4 => 'Sedang Dikirim',
                5 => 'Selesai',
                6 => 'Dibatalkan',
            ];
            $colorMap = [
                1 => 'warning',
                2 => 'info',
                3 => 'secondary',
                4 => 'secondary',
                5 => 'success',
                6 => 'danger',
            ];
            $trx->status_text  = $statusMap[$trx->tst_status] ?? 'Status Tidak Dikenal';
            $trx->status_color = $colorMap[$trx->tst_status] ?? 'dark';
        }

        // payment_text (tetap manual numeric mapping)
        $trx->payment_text = match ($trx->tst_payment_status) {
            1 => 'Menunggu Pembayaran',
            2 => 'Pembayaran Berhasil',
            3 => 'Pembayaran Gagal',
            default => 'Status Tidak Dikenal'
        };

        return $trx;
    }

    // =================== BUYER ===================

    public function productCheckout($prd_id)
    {
        $product = Product::findOrFail($prd_id);
        return view('livewire.user.transaction.checkout', compact('product'));
    }

    public function productInvoice($id)
    {
        $transaction = Transaction::with(['items', 'shipment'])
            ->where('tst_id', $id)
            ->where('tst_buyer_id', Auth::user()->usr_id)
            ->firstOrFail();

        $transaction = $this->applyStatus($transaction);

        $address = $this->getAddressFromLatLng(
        $transaction->tst_latitude,
        $transaction->tst_longitude
    );
        return view('livewire.user.transaction.invoice', compact('transaction'));
    }

    public function payNow($id)
    {
        $transaction = Transaction::where('tst_id', $id)
            ->where('tst_buyer_id', Auth::user()->usr_id)
            ->firstOrFail();

        if ($transaction->tst_payment_status == 2) {
            return back()->with('info', 'Pembayaran sudah selesai.');
        }

        $transaction->update([
            'tst_payment_status' => 2, // 2 = success
            'tst_status'         => 2, // misalnya "paid"
            'tst_updated_by'     => Auth::user()->usr_id,
        ]);

        return redirect()
            ->route('checkout.product.invoice', $transaction->tst_id)
            ->with('success', 'Pembayaran berhasil diselesaikan.');
    }

    public function indexOrders()
    {
        $orders = Transaction::where('tst_buyer_id', Auth::user()->usr_id)
            ->orderByDesc('tst_created_at')
            ->paginate(12);

        if (method_exists($orders, 'through')) {
            $orders = $orders->through(function ($order) {
                return $this->applyStatus($order);
            });
        } else {
            $orders->getCollection()->transform(function ($order) {
                return $this->applyStatus($order);
            });
        }

        return view('livewire.user.transaction.orders', [
            'title' => 'Pesanan Saya',
            'orders' => $orders
        ]);
    }

    public function detailOrders($id)
    {
        $order = Transaction::with(['items.product', 'seller'])
            ->where('tst_buyer_id', Auth::user()->usr_id)
            ->where('tst_id', $id)
            ->firstOrFail();

        $order = $this->applyStatus($order);

        return view('livewire.user.transaction.orders-detail', [
            'title' => 'Detail Pesanan',
            'order' => $order
        ]);
    }

    public function productStore(Request $request, $prd_id)
    {
        $request->validate([
            'quantity'        => 'required|integer|min:1',
            'payment_method'  => 'required|string',
            'address'         => 'nullable|string|max:500',
            'latitude'        => 'nullable|numeric',
            'longitude'       => 'nullable|numeric',
        ]);

        $product  = Product::findOrFail($prd_id);
        $quantity = $request->quantity;
        $subtotal = $product->prd_price * $quantity;

        // Buat transaksi
        $transaction = Transaction::create([
            'tst_invoice'        => 'RRQ-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'tst_buyer_id'       => Auth::user()->usr_id,
            'tst_seller_id'      => $product->prd_created_by,
            'tst_subtotal'       => $subtotal,
            'tst_total'          => $subtotal,
            'tst_shipping_cost'  => 0,
            'tst_payment_method' => $request->payment_method,
            'tst_payment_status' => 1,
            'tst_status'         => 1,
            'tst_created_by'     => Auth::user()->usr_id,
            'tst_updated_by'     => Auth::user()->usr_id,
            'tst_expires_at'     => now()->addMinutes(30),
        ]);

        TransactionItem::create([
            'tst_item_transaction_id' => $transaction->tst_id,
            'tst_item_product_id'     => $product->prd_id,
            'tst_item_product_name'   => $product->prd_name,
            'tst_item_quantity'       => $quantity,
            'tst_item_price'          => $product->prd_price,
            'tst_item_subtotal'       => $subtotal,
        ]);

        // Shipment
        Shipments::create([
            'shp_transaction_id' => $transaction->tst_id,
            'shp_status'         => 'pending',
            'shp_address'        => $request->address,
            'shp_latitude'       => $request->latitude,
            'shp_longitude'      => $request->longitude,
            'shp_created_by'     => Auth::user()->usr_id,
        ]);

        // Redirect ke invoice final
        return redirect()
            ->route('checkout.product.invoice', $transaction->tst_id)
            ->with('success', 'Invoice berhasil dibuat.');
    }

    /**
     * Riwayat / list transaksi pembeli (non-paginated view)
     */
    public function index()
    {
        $transactions = Transaction::with(['items', 'shipment'])
            ->where('tst_buyer_id', Auth::user()->usr_id)
            ->latest('tst_created_at')
            ->get()
            ->transform(function ($trx) {
                return $this->applyStatus($trx);
            });

        return view('livewire.user.transaction.index', compact('transactions'));
    }

    /**
     * Checkout keranjang
     */
    public function checkout()
    {
        $cart = Cart::with(['items.product'])
            ->where('crs_buyer_id', Auth::user()->usr_id)
            ->where('crs_status', 'active')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong.');
        }

        return view('livewire.user.transaction.checkout', compact('cart'));
    }

    /**
     * Store transaksi keranjang
     */
    public function Store(Request $request, $prd_id)
    {
        $request->validate([
            'quantity'        => 'required|integer|min:1',
            'payment_method'  => 'required|string',
            'address'         => 'required|string|max:500',
            'latitude'        => 'nullable|numeric',
            'longitude'       => 'nullable|numeric',
        ]);

        $product = Product::findOrFail($prd_id);
        $buyerId = Auth::user()->usr_id;
        $sellerId = $product->prd_created_by;

        $qty = $request->quantity;
        $subtotal = $product->prd_price * $qty;

        // === CREATE TRANSACTION ==================================
        $transaction = Transaction::create([
            'tst_invoice'        => 'RRQ-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'tst_buyer_id'       => $buyerId,
            'tst_seller_id'      => $sellerId,

            'tst_subtotal'       => $subtotal,
            'tst_total'          => $subtotal,

            'tst_payment_method' => $request->payment_method,
            'tst_payment_status' => 'pending',   // default
            'tst_status'         => 'waiting',   // default

            'tst_created_by'     => $buyerId,
            'tst_updated_by'     => $buyerId,
        ]);

        // === ADD ITEM =============================================
        TransactionItem::create([
            'tst_item_transaction_id' => $transaction->tst_id,
            'tst_item_product_id'     => $product->prd_id,
            'tst_item_product_name'   => $product->prd_name,
            'tst_item_quantity'       => $qty,
            'tst_item_price'          => $product->prd_price,
            'tst_item_subtotal'       => $subtotal,
        ]);

        Shipments::create([
            'shp_transaction_id' => $transaction->tst_id,
            'shp_status'         => 'pending',
            'shp_address'        => $request->address,
            'shp_latitude'       => $request->latitude,
            'shp_longitude'      => $request->longitude,
            'shp_created_by'     => Auth::user()->usr_id,
        ]);


        // === REDIRECT ==============================================
        return redirect()
            ->route('checkout.product.invoice', $transaction->tst_id)
            ->with('success', 'Transaksi berhasil dibuat. Lanjutkan pembayaran.');
    }


    /**
     * Detail transaksi pembeli (show)
     */
    public function show($id)
    {
        $transaction = Transaction::with(['items', 'shipment'])
            ->where('tst_id', $id)
            ->where('tst_buyer_id', Auth::user()->usr_id)
            ->firstOrFail();

        $transaction = $this->applyStatus($transaction);

        return view('livewire.user.transaction.show', compact('transaction'));
    }

    // =================== ADMIN / SELLER ===================

    public function adminIndex(Request $request)
    {
        $search = $request->search;

        $query = Transaction::with(['items', 'shipment'])
            ->latest('tst_created_at');

        if ($search) {
            $query->where('tst_invoice', 'like', "%$search%");
        }

        $transactions = $query->paginate(12);

        if (method_exists($transactions, 'through')) {
            $transactions = $transactions->through(fn($t) => $this->applyStatus($t));
        } else {
            $transactions->getCollection()->transform(fn($t) => $this->applyStatus($t));
        }

        return view('livewire.admin.transaction.index', compact('transactions'));
    }

    public function actions($id)
    {
        $transaction = Transaction::with(['items', 'buyer', 'seller', 'shipment'])
            ->findOrFail($id);

        $transaction = $this->applyStatus($transaction);

        return view('livewire.admin.transaction.actions', compact('transaction'));
    }

    public function adminShow($id)
    {
        $transaction = Transaction::with(['items', 'buyer', 'seller', 'shipment'])
            ->findOrFail($id);

        $transaction = $this->applyStatus($transaction);

        return view('livewire.admin.transaction.detail', compact('transaction'));
    }

    public function adminConfirmPayment($id)
    {
        $transaction = Transaction::findOrFail($id);

        // Jika sudah sukses (gunakan numeric check sesuai model lama)
        if ($transaction->tst_payment_status == 2) {
            return back()->with('info', 'Pembayaran sudah dikonfirmasi.');
        }

        // Jika kamu punya method markPaymentSuccess di model, pakai itu.
        // Tetap panggil agar behaviour tetap sama
        if (method_exists($transaction, 'markPaymentSuccess')) {
            $transaction->markPaymentSuccess(Auth::user()->usr_id);
        } else {
            // fallback: update langsung
            $transaction->update([
                'tst_payment_status' => 2,
                'tst_status'         => 2, // paid
                'tst_updated_by'     => Auth::user()->usr_id,
            ]);
        }

        return back()->with('success', 'Pembayaran dikonfirmasi.');
    }

    public function adminCancelTransaction($id)
    {
        $transaction = Transaction::findOrFail($id);

        if (method_exists($transaction, 'markPaymentCancelled')) {
            $transaction->markPaymentCancelled(Auth::user()->usr_id);
        }

        $transaction->update([
            'tst_status'     => 6,
            'tst_updated_by' => Auth::user()->usr_id,
        ]);

        return back()->with('success', 'Transaksi dibatalkan.');
    }

    public function adminShipOrder($id)
    {
        $transaction = Transaction::with('shipment')->findOrFail($id);

        if (!$transaction->shipment) {
            return back()->with('error', 'Data pengiriman tidak ditemukan.');
        }

        $transaction->shipment->update([
            'shp_status'     => 'sending',
            'shp_updated_by' => Auth::user()->usr_id,
        ]);

        $transaction->update([
            'tst_status'     => 4,
            'tst_updated_by' => Auth::user()->usr_id,
        ]);

        return back()->with('success', 'Pesanan dikirim.');
    }

    public function adminFinishOrder($id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->update([
            'tst_status'     => 5,
            'tst_updated_by' => Auth::user()->usr_id,
        ]);

        return back()->with('success', 'Pesanan selesai.');
    }

    public function getAddressFromLatLng($lat, $lng)
    {
        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lng}&zoom=18&addressdetails=1";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // WAJIB! Jika tidak → 403 Forbidden
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: MyApp/1.0 (contact: example@gmail.com)'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (!$data || !isset($data['address'])) {
            return [
                'province' => '-',
                'city'     => '-',
                'district' => '-',
            ];
        }

        $addr = $data['address'];

        return [
            'province' => $addr['state']        ?? '-',
            'city'     => $addr['city']         ?? ($addr['town'] ?? ($addr['village'] ?? '-')),
            'district' => $addr['suburb']       ?? ($addr['county'] ?? '-'),
        ];
    }

}

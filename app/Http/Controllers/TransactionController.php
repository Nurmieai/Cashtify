<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Shipments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Product;

class TransactionController extends Controller
{
    private function applyStatus($trx)
    {
        if (! $trx) {
            return $trx;
        }

        $statusMap = [
            'pending'  => 'Menunggu Pembayaran',
            'paid'     => 'Pembayaran Berhasil',
            'verified' => 'Menunggu Konfirmasi Penjual',
            'sent'     => 'Sedang Dikirim',
            'done'     => 'Selesai',
            'cancelled'=> 'Dibatalkan',
            'waiting'  => 'Menunggu',
        ];

        $colorMap = [
            'pending'  => 'warning',
            'paid'     => 'info',
            'verified' => 'secondary',
            'sent'     => 'secondary',
            'done'     => 'success',
            'cancelled'=> 'danger',
            'waiting'  => 'dark',
        ];

        $trx->status_text  = $statusMap[$trx->tst_status] ?? 'Status Tidak Dikenal';
        $trx->status_color = $colorMap[$trx->tst_status] ?? 'dark';

        $paymentMap = [
            'pending'   => 'Menunggu Pembayaran',
            'paid'      => 'Pembayaran Berhasil',
            'failed'    => 'Pembayaran Gagal',
            'cancelled' => 'Pembayaran Dibatalkan',
        ];

        $trx->payment_text = $paymentMap[$trx->tst_payment_status] ?? 'Status Tidak Dikenal';

        return $trx;
    }

    public function productCheckout($prd_id)
    {
        $product = Product::findOrFail($prd_id);

        return view('livewire.user.transaction.checkout', [
            'product' => $product,
            'cart_items' => null,
        ]);
    }

    public function productInvoice($id)
    {
        $transaction = Transaction::with(['items', 'shipment'])
            ->where('tst_id', $id)
            ->where('tst_buyer_id', Auth::user()->usr_id)
            ->firstOrFail();

        $transaction = $this->applyStatus($transaction);

        $address = $this->getAddressFromLatLng(
            $transaction->tst_latitude ?? null,
            $transaction->tst_longitude ?? null
        );

        return view('livewire.user.transaction.invoice', compact('transaction', 'address'));
    }

    public function payNow($id)
    {
        $transaction = Transaction::where('tst_id', $id)
            ->where('tst_buyer_id', Auth::user()->usr_id)
            ->firstOrFail();

        if ($transaction->tst_payment_status === 'paid') {
            return back()->with('info', 'Pembayaran sudah selesai.');
        }

        $transaction->update([
            'tst_payment_status' => 'paid',
            'tst_status'         => 'paid',
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
            $orders = $orders->through(fn($order) => $this->applyStatus($order));
        } else {
            $orders->getCollection()->transform(fn($order) => $this->applyStatus($order));
        }

        return view('livewire.user.transaction.orders', [
            'title' => 'Pesanan Saya',
            'orders' => $orders,
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
            'order' => $order,
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

        $transaction = Transaction::create([
            'tst_invoice'        => 'RRQ-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'tst_buyer_id'       => Auth::user()->usr_id,
            'tst_seller_id'      => $product->prd_created_by,
            'tst_subtotal'       => $subtotal,
            'tst_total'          => $subtotal,
            'tst_shipping_cost'  => 0,
            'tst_payment_method' => $request->payment_method,
            'tst_payment_status' => 'pending',
            'tst_status'         => 'pending',
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

        Shipments::create([
            'shp_transaction_id' => $transaction->tst_id,
            'shp_status'         => 'pending',
            'shp_address'        => $request->address,
            'shp_latitude'       => $request->latitude,
            'shp_longitude'      => $request->longitude,
            'shp_created_by'     => Auth::user()->usr_id,
        ]);

        return redirect()
            ->route('checkout.product.invoice', $transaction->tst_id)
            ->with('success', 'Invoice berhasil dibuat.');
    }

    public function index()
    {
        $transactions = Transaction::with(['items', 'shipment'])
            ->where('tst_buyer_id', Auth::user()->usr_id)
            ->latest('tst_created_at')
            ->get()
            ->transform(fn($trx) => $this->applyStatus($trx));

        return view('livewire.user.transaction.index', compact('transactions'));
    }

    public function checkoutCart()
    {
        $buyerId = Auth::user()->usr_id;

        $cart = Cart::with(['items.product'])
            ->where('crs_buyer_id', $buyerId)
            ->where('crs_status', 'active')
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong.');
        }

        return view('livewire.user.transaction.checkout', [
            'product' => null,
            'cart_items' => $cart->items,
        ]);
    }

    public function checkoutCartStore(Request $request)
    {
        $request->validate([
            'payment_method'  => 'required|string',
            'address'         => 'required|string|max:500',
            'latitude'        => 'nullable|numeric',
            'longitude'       => 'nullable|numeric',
        ]);

        $buyerId = Auth::user()->usr_id;

        $cart = Cart::with(['items.product'])
            ->where('crs_buyer_id', $buyerId)
            ->where('crs_status', 'active')
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong.');
        }

        $subtotal = $cart->items->sum(fn($item) =>
            $item->product->prd_price * $item->crs_item_quantity
        );

        $sellerId = $cart->items->first()->product->prd_created_by;

        $transaction = Transaction::create([
            'tst_invoice'        => 'RRQ-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'tst_buyer_id'       => $buyerId,
            'tst_seller_id'      => $sellerId,
            'tst_subtotal'       => $subtotal,
            'tst_total'          => $subtotal,
            'tst_payment_method' => $request->payment_method,
            'tst_payment_status' => 'pending',
            'tst_status'         => 'pending',
            'tst_latitude'       => $request->latitude,
            'tst_longitude'      => $request->longitude,
            'tst_created_by'     => $buyerId,
            'tst_expires_at'     => now()->addMinutes(30),
        ]);

        foreach ($cart->items as $item) {
            TransactionItem::create([
                'tst_item_transaction_id' => $transaction->tst_id,
                'tst_item_product_id'     => $item->product->prd_id,
                'tst_item_product_name'   => $item->product->prd_name,
                'tst_item_quantity'       => $item->crs_item_quantity,
                'tst_item_price'          => $item->product->prd_price,
                'tst_item_subtotal'       => $item->product->prd_price * $item->crs_item_quantity,
            ]);
        }

        Shipments::create([
            'shp_transaction_id' => $transaction->tst_id,
            'shp_status'         => 'pending',
            'shp_address'        => $request->address,
            'shp_latitude'       => $request->latitude,
            'shp_longitude'      => $request->longitude,
            'shp_created_by'     => $buyerId,
        ]);

        $cart->crs_status = 'ordered';
        $cart->save();

        return redirect()
            ->route('checkout.product.invoice', $transaction->tst_id)
            ->with('success', 'Checkout berhasil, lanjutkan pembayaran.');
    }

    public function show($id)
    {
        $transaction = Transaction::with(['items', 'shipment'])
            ->where('tst_id', $id)
            ->where('tst_buyer_id', Auth::user()->usr_id)
            ->firstOrFail();

        $transaction = $this->applyStatus($transaction);

        return view('livewire.user.transaction.show', compact('transaction'));
    }

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

    public function adminShipmentSave(Request $request, $id)
    {
        $request->validate([
            'shp_courier'       => 'required|string',
            'shp_service'       => 'required|string',
            'shp_tracking_code' => 'required|string',
            'shp_status'        => 'required|string',
            'shp_notes'         => 'nullable|string',
        ]);

        $transaction = Transaction::findOrFail($id);

        $shipment = $transaction->shipment;

        $payload = [
            'shp_courier'       => $request->shp_courier,
            'shp_service'       => $request->shp_service,
            'shp_tracking_code' => $request->shp_tracking_code,
            'shp_status'        => $request->shp_status,
            'shp_notes'         => $request->shp_notes,
            'shp_updated_by'    => Auth::user()->usr_id,
        ];

        if ($request->shp_status === 'sending') {
            $payload['shp_sent_at'] = now();
        }

        if ($request->shp_status === 'delivered') {
            $payload['shp_delivered_at'] = now();
        }

        if ($shipment) {
            $shipment->update($payload);
        } else {
            $payload['shp_transaction_id'] = $id;
            $payload['shp_created_by']     = Auth::user()->usr_id;
            Shipments::create($payload);
        }
        return back()->with('success', 'Informasi pengiriman berhasil diperbarui.');
    }


    public function adminConfirmPayment($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->tst_payment_status === 'paid') {
            return back()->with('info', 'Pembayaran sudah dikonfirmasi.');
        }

        if (method_exists($transaction, 'markPaymentSuccess')) {
            $transaction->markPaymentSuccess(Auth::user()->usr_id);
        } else {
            $transaction->update([
                'tst_payment_status' => 'paid',
                'tst_status'         => 'paid',
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
        } else {
            $transaction->update([
                'tst_payment_status' => 'cancelled',
                'tst_status'         => 'cancelled',
                'tst_updated_by'     => Auth::user()->usr_id,
            ]);
        }

        return back()->with('success', 'Transaksi dibatalkan.');
    }

    public function adminShipOrder($id)
    {
        $transaction = Transaction::with('shipment')->findOrFail($id);

        if (! $transaction->shipment) {
            return back()->with('error', 'Data pengiriman tidak ditemukan.');
        }

        $transaction->shipment->update([
            'shp_status'     => 'sending',
            'shp_updated_by' => Auth::user()->usr_id,
        ]);

        $transaction->update([
            'tst_status'     => 'sent',
            'tst_updated_by' => Auth::user()->usr_id,
        ]);

        return back()->with('success', 'Pesanan dikirim.');
    }

    public function adminFinishOrder($id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->update([
            'tst_status'     => 'done',
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

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: MyApp/1.0 (contact: example@gmail.com)'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (! $data || ! isset($data['address'])) {
            return [
                'province' => '-',
                'city'     => '-',
                'district' => '-',
            ];
        }

        $addr = $data['address'];

        return [
            'province' => $addr['state'] ?? '-',
            'city'     => $addr['city'] ?? ($addr['town'] ?? ($addr['village'] ?? '-')),
            'district' => $addr['suburb'] ?? ($addr['county'] ?? '-'),
        ];
    }
}

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
    // =================== BUYER ===================

    /**
     * Halaman checkout 1 produk
     */
    public function productCheckout($prd_id)
    {
        $product = Product::findOrFail($prd_id);
        return view('livewire.user.transaction.checkout', compact('product'));
    }

    /**
     * Tampilkan invoice transaksi (setelah transaksi dibuat)
     */
    public function productInvoice($id)
    {
        $transaction = Transaction::with(['items', 'shipment'])
            ->where('tst_id', $id)
            ->where('tst_buyer_id', Auth::user()->usr_id)
            ->firstOrFail();

        return view('livewire.user.transaction.invoice', compact('transaction'));
    }

    public function payNow($id)
    {
        $transaction = Transaction::where('tst_id', $id)
            ->where('tst_buyer_id', Auth::user()->usr_id)
            ->firstOrFail();

        // Jika sudah dibayar
        if ($transaction->tst_payment_status == 2) {
            return back()->with('info', 'Pembayaran sudah selesai.');
        }

        // Update status pembayaran
        $transaction->update([
            'tst_payment_status' => 2, // 2 = success
            'tst_status'         => 2, // misalnya "paid"
            'tst_updated_by'     => Auth::user()->usr_id,
        ]);

        return redirect()
            ->route('checkout.product.invoice', $transaction->tst_id)
            ->with('success', 'Pembayaran berhasil diselesaikan.');
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
            'tst_expires_at' => now()->addMinutes(30),
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
     * Riwayat transaksi pembeli
     */
    public function index()
    {
        $transactions = Transaction::with(['items', 'shipment'])
            ->where('tst_buyer_id', Auth::user()->usr_id)
            ->latest('tst_created_at')
            ->get();

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
    public function store(Request $request)
    {
        $request->validate([
            'address'   => 'required|string|max:500',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $cart = Cart::with(['items.product'])
            ->where('crs_buyer_id', Auth::user()->usr_id)
            ->where('crs_status', 'active')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Keranjang kosong.');
        }

        // Hitung subtotal
        $subtotal = 0;
        foreach ($cart->items as $item) {
            $subtotal += $item->product->prd_price * $item->crs_item_quantity;
        }

        $sellerId = $cart->items->first()->product->prd_created_by;

        // Buat transaksi
        $transaction = Transaction::create([
            'tst_invoice'        => 'RRQ-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'tst_buyer_id'       => Auth::user()->usr_id,
            'tst_seller_id'      => $sellerId,
            'tst_subtotal'       => $subtotal,
            'tst_total'          => $subtotal,
            'tst_payment_method' => 'manual',
            'tst_payment_status' => 1,
            'tst_status'         => 1,
            'tst_created_by'     => Auth::user()->usr_id,
            'tst_updated_by'     => Auth::user()->usr_id,
        ]);

        // Items
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

        // Kosongkan cart
        CartItem::where('cri_cart_id', $cart->crs_id)->delete();
        $cart->update([
            'crs_total_items' => 0,
            'crs_total_price' => 0,
            'crs_status'      => 'checked_out',
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
            ->with('success', 'Transaksi berhasil dibuat. Lanjutkan pembayaran.');
    }

    /**
     * Detail transaksi pembeli
     */
    public function show($id)
    {
        $transaction = Transaction::with(['items', 'shipment'])
            ->where('tst_id', $id)
            ->where('tst_buyer_id', Auth::user()->usr_id)
            ->firstOrFail();

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

        $transactions = $query->paginate(12)->withQueryString();
        return view('livewire.admin.transaction.index', compact('transactions'));
    }

    public function adminShow($id)
    {
        $transaction = Transaction::with(['items', 'buyer', 'seller', 'shipment'])
            ->findOrFail($id);

        return view('livewire.admin.transaction.detail', compact('transaction'));
    }
    public function actions($id)
    {
        $transaction = Transaction::with(['items', 'buyer', 'seller', 'shipment'])
            ->findOrFail($id);

        return view('livewire.admin.transaction.actions', compact('transaction'));
    }


    public function adminConfirmPayment($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->tst_payment_status == 'success') {
            return back()->with('info', 'Pembayaran sudah dikonfirmasi.');
        }

        $transaction->markPaymentSuccess(Auth::user()->usr_id);

        return back()->with('success', 'Pembayaran dikonfirmasi.');
    }

    public function adminCancelTransaction($id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->markPaymentCancelled(Auth::user()->usr_id);
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
}

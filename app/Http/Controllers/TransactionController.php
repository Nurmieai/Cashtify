<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Cart;
use App\Models\Shipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    // ------------------- BUYER -------------------

    // Riwayat transaksi / pesanan saya
    public function index()
    {
        $transactions = Transaction::with(['items', 'shipment'])
            ->where('tst_buyer_id', Auth::user()->usr_id)
            ->orderByDesc('tst_created_at')
            ->get();

        return view('livewire.user.transaction.index', compact('transactions'));
    }

    // Halaman checkout dari keranjang
    public function checkout()
    {
        $cart = Cart::with(['product'])
            ->where('crs_user_id', Auth::user()->usr_id)
            ->get();

        if ($cart->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong.');
        }

        return view('livewire.user.transaction.checkout', compact('cart'));
    }

    // Simpan transaksi setelah checkout
    public function store(Request $request)
    {
        $request->validate([
            'address'   => 'required|string|max:500',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $cart = Cart::with(['product'])
            ->where('crs_user_id', Auth::user()->usr_id)
            ->get();

        if ($cart->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong.');
        }

        $subtotal = $cart->sum(fn($c) => ($c->product->prd_price * $c->crs_item_quantity));
        $sellerId = $cart->first()->product->prd_created_by;

        $transaction = Transaction::create([
            'tst_invoice'        => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'tst_buyer_id'       => Auth::user()->usr_id,
            'tst_seller_id'      => $sellerId,
            'tst_total'          => $subtotal,
            'tst_subtotal'       => $subtotal,
            'tst_discount'       => 0,
            'tst_shipping_cost'  => 0,
            'tst_payment_method' => 'manual',
            'tst_payment_status' => 0, // 0 = belum bayar
            'tst_status'         => 0, // 0 = menunggu pembayaran
            'tst_notes'          => null,
            'tst_created_by'     => Auth::user()->usr_id,
            'tst_updated_by'     => Auth::user()->usr_id,
        ]);

        // Simpan item transaksi
        foreach ($cart as $item) {
            TransactionItem::create([
                'tst_item_transaction_id' => $transaction->tst_id,
                'tst_item_product_id'     => $item->product->prd_id,
                'tst_item_product_name'   => $item->product->prd_name,
                'tst_item_quantity'       => $item->crs_item_quantity,
                'tst_item_price'          => $item->product->prd_price,
                'tst_item_subtotal'       => $item->product->prd_price * $item->crs_item_quantity,
            ]);
        }

        // Kosongkan keranjang
        Cart::where('crs_user_id', Auth::user()->usr_id)->delete();

        // Simpan shipment
        Shipment::create([
            'shp_transaction_id' => $transaction->tst_id,
            'shp_status'         => 'pending',
            'shp_address'        => $request->address,
            'shp_latitude'       => $request->latitude,
            'shp_longitude'      => $request->longitude,
            'shp_created_by'     => Auth::user()->usr_id,
        ]);

        return redirect()->route('transactions.show', $transaction->tst_id)
            ->with('success', 'Transaksi berhasil dibuat. Silakan lanjutkan pembayaran.');
    }

    // Detail transaksi
    public function show($id)
    {
        $transaction = Transaction::with(['items', 'shipment'])
            ->where('tst_id', $id)
            ->where('tst_buyer_id', Auth::user()->usr_id)
            ->firstOrFail();

        return view('livewire.user.transaction.show', compact('transaction'));
    }

    // ------------------- ADMIN / PENJUAL -------------------

    public function adminIndex(Request $request)
    {
        $search = $request->input('search');

        $query = Transaction::query()
            ->with(['items', 'shipment'])
            ->latest('tst_created_at');

        if ($search) {
            $query->where('tst_invoice', 'like', "%{$search}%")
                  ->orWhere('tst_status', 'like', "%{$search}%");
        }

        $transactions = $query->paginate(12)->withQueryString();

        return view('livewire.admin.transaction.index', compact('transactions'));
    }

    public function adminShow($id)
    {
        $transaction = Transaction::with(['items', 'buyer', 'seller', 'shipment'])
            ->findOrFail($id);

        return view('livewire.admin.transaction.show', compact('transaction'));
    }

    public function adminUpdateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|integer']);

        $transaction = Transaction::findOrFail($id);
        $transaction->tst_status = $request->status;
        $transaction->tst_updated_by = Auth::user()->usr_id;
        $transaction->save();

        return back()->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function adminConfirmPayment($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->tst_payment_status == 1) {
            return back()->with('info', 'Pembayaran sudah dikonfirmasi sebelumnya.');
        }

        $transaction->tst_payment_status = 1;
        $transaction->tst_status = 1;
        $transaction->tst_updated_by = Auth::user()->usr_id;
        $transaction->save();

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function adminCancelTransaction($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->tst_status = 3;
        $transaction->tst_updated_by = Auth::user()->usr_id;
        $transaction->save();

        return back()->with('success', 'Transaksi berhasil dibatalkan.');
    }

    public function adminShipOrder($id)
    {
        $transaction = Transaction::with('shipment')->findOrFail($id);

        if (!$transaction->shipment) {
            return back()->with('error', 'Data pengiriman tidak ditemukan.');
        }

        $transaction->shipment->update(['shp_status' => 'shipped']);
        $transaction->update([
            'tst_status' => 4,
            'tst_updated_by' => Auth::user()->usr_id,
        ]);

        return back()->with('success', 'Pesanan telah ditandai sebagai dikirim.');
    }

    public function adminFinishOrder($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->tst_status = 2;
        $transaction->tst_updated_by = Auth::user()->usr_id;
        $transaction->save();

        return back()->with('success', 'Pesanan telah selesai.');
    }
}

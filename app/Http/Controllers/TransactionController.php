<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    // ------------------- BUYER -------------------
    public function index()
    {
        $transactions = Transaction::where('tst_buyer_id', Auth::user()->usr_id)
            ->orderByDesc('tst_created_at')
            ->get();

        return view('livewire.user.transaction.index', compact('transactions'));
    }

    public function create()
    {
        $cart = Cart::with('product')
            ->where('crs_user_id', Auth::user()->usr_id)
            ->get();

        if ($cart->isEmpty()) {
            return redirect()->route('cart')
                ->with('error', 'Keranjang kosong.');
        }

        return view('livewire.user.transaction.create', compact('cart'));
    }

    public function store(Request $request)
    {
        $cart = Cart::with('product')
            ->where('crs_user_id', Auth::user()->usr_id)
            ->get();

        if ($cart->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong.');
        }

        $subtotal = $cart->sum(fn($c) => $c->product->prd_price);
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
            'tst_payment_status' => 0,
            'tst_status'         => 0,
            'tst_notes'          => null,
            'tst_created_by'     => Auth::user()->usr_id,
            'tst_updated_by'     => Auth::user()->usr_id,
        ]);

        foreach ($cart as $item) {
            TransactionItem::create([
                'tst_item_transaction_id' => $transaction->tst_id,
                'tst_item_product_id'     => $item->product->prd_id,
                'tst_item_product_name'   => $item->product->prd_name,
                'tst_item_quantity'       => 1,
                'tst_item_price'          => $item->product->prd_price,
                'tst_item_subtotal'       => $item->product->prd_price,
            ]);
        }

        Cart::where('crs_user_id', Auth::user()->usr_id)->delete();

        return redirect()->route('transactions.show', $transaction->tst_id)
            ->with('success', 'Transaksi berhasil dibuat.');
    }

    public function show($id)
    {
        $transaction = Transaction::with('items')
            ->where('tst_id', $id)
            ->where('tst_buyer_id', Auth::user()->usr_id)
            ->firstOrFail();

        return view('livewire.user.transaction.show', compact('transaction'));
    }

    // ------------------- ADMIN / PENJUAL -------------------

    public function adminIndex(Request $request)
    {
        $search = $request->input('search');

        $query = Transaction::query()->latest('tst_created_at');

        if ($search) {
            $query->where('tst_invoice', 'like', "%{$search}%")
                  ->orWhere('tst_status', 'like', "%{$search}%");
        }

        $transactions = $query->paginate(12)->withQueryString();

        return view('livewire.admin.transaction.index', compact('transactions'));
    }

    public function adminShow($id)
    {

        $transaction = Transaction::with('items')->findOrFail($id);

        return view('livewire.admin.transaction.show', compact('transaction'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Show active cart.
     */
    public function index()
    {
        $buyerId = Auth::user()->usr_id;

        $cart = Cart::with(['items.product'])
            ->where('crs_buyer_id', $buyerId)
            ->where('crs_status', 'active')
            ->first();

        return view('livewire.user.cart.index', compact('cart'));
    }

    /**
     * Add product to cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity'   => 'required|integer|min:1',
        ]);

        $buyerId = Auth::user()->usr_id;
        $product = Product::findOrFail($request->product_id);
        $qty     = $request->quantity;

        // 1. Find or create active cart
        $cart = Cart::firstOrCreate(
            [
                'crs_buyer_id' => $buyerId,
                'crs_status'   => 'active',
            ],
            [
                'crs_total' => 0,
            ]
        );

        // 2. Existing item?
        $item = CartItem::where('crs_item_cart_id', $cart->crs_id)
            ->where('crs_item_product_id', $product->prd_id)
            ->first();

        if ($item) {
            // update qty
            $item->crs_item_quantity += $qty;
            $item->crs_item_subtotal = $item->crs_item_quantity * $item->crs_item_price;
            $item->save();
        } else {
            // create new item
            CartItem::create([
                'crs_item_cart_id'    => $cart->crs_id,
                'crs_item_product_id' => $product->prd_id,
                'crs_item_quantity'   => $qty,
                'crs_item_price'      => $product->prd_price,
                'crs_item_subtotal'   => $product->prd_price * $qty,
            ]);
        }

        $cart->updateTotals();

        return redirect()->route('cart')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Update item quantity.
     */
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::with('cart')->findOrFail($itemId);

        $item->crs_item_quantity = $request->quantity;
        $item->crs_item_subtotal = $item->crs_item_price * $request->quantity;
        $item->save();

        $item->cart->updateTotals();

        return redirect()->back()->with('success', 'Jumlah produk diperbarui.');
    }

    /**
     * Remove item from cart.
     */
    public function remove($itemId)
    {
        $item = CartItem::with('cart')->findOrFail($itemId);
        $cart = $item->cart;

        $item->delete();

        $cart->updateTotals();

        return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
    }

    /**
     * Clear all items in the cart.
     */
    public function clear()
    {
        $buyerId = Auth::user()->usr_id;

        $cart = Cart::where('crs_buyer_id', $buyerId)
            ->where('crs_status', 'active')
            ->first();

        if (!$cart) {
            return redirect()->back();
        }

        $cart->items()->delete();
        $cart->updateTotals();

        return redirect()->back()->with('success', 'Keranjang dikosongkan.');
    }
}

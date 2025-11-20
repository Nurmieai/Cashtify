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
     * Show cart details for logged-in buyer.
     */
    public function index()
    {
        $buyerId = Auth::id();

        $cart = Cart::with('items')
            ->where('crs_buyer_id', $buyerId)
            ->where('crs_status', 'active')
            ->first();

        return view('buyer.cart.index', compact('cart'));
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

        $buyerId = Auth::id();
        $product = Product::findOrFail($request->product_id);

        // 1. Find or create active cart
        $cart = Cart::firstOrCreate(
            [
                'crs_buyer_id' => $buyerId,
                'crs_status'   => 'active',
            ],
            [
                'crs_total_items' => 0,
                'crs_total_price' => 0,
            ]
        );

        // 2. Find existing item in cart
        $item = CartItem::where('crs_item_cart_id', $cart->crs_id)
            ->where('crs_item_product_id', $product->prd_id)
            ->first();

        if ($item) {
            // Update quantity
            $item->crs_item_quantity += $request->quantity;
            $item->crs_item_subtotal = $item->crs_item_quantity * $item->crs_item_price;
            $item->save();
        } else {
            // Add new item
            CartItem::create([
                'crs_item_cart_id'   => $cart->crs_id,
                'crs_item_product_id'=> $product->prd_id,
                'crs_item_product_name' => $product->prd_name,
                'crs_item_price'     => $product->prd_price,
                'crs_item_quantity'  => $request->quantity,
                'crs_item_subtotal'  => $product->prd_price * $request->quantity,
            ]);
        }

        // 3. Recalculate cart totals
        $cart->updateTotals();

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Update quantity of a cart item.
     */
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::findOrFail($itemId);

        $item->crs_item_quantity = $request->quantity;
        $item->crs_item_subtotal = $item->crs_item_price * $request->quantity;
        $item->save();

        // Recalculate cart totals
        $item->cart->updateTotals();

        return redirect()->back()->with('success', 'Jumlah produk diperbarui.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove($itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $cart = $item->cart;

        $item->delete();

        // Recalculate cart totals
        $cart->updateTotals();

        return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
    }

    /**
     * Clear the entire cart.
     */
    public function clear()
    {
        $buyerId = Auth::id();

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

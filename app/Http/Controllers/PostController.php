<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Show products list (landing pembeli)
     */
    public function index(Request $request)
    {
        $locations = Location::all();

        $query = Product::orderByDesc('usr_created_at');

        // Filter berdasarkan lokasi pembeli (pre-order)
        if ($request->has('location') && $request->location != '') {
            $query->where('lcn_id', $request->location);
        }

        $products = $query->paginate(8);

        return view('posts.index', [
            'products'  => $products,
            'locations' => $locations,
        ]);
    }

    /**
     * Create product
     */
    public function create()
    {
        $locations = Location::all();
        return view('posts.create', compact('locations'));
    }

    /**
     * Store product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prd_name'        => 'required|string',
            'prd_description' => 'required|string',
            'prd_price'       => 'required|integer',
            'prd_status'      => 'required|in:tersedia,tidak tersedia',
            'prd_card_url'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'lcn_id'          => 'required|exists:locations,lcn_id',
        ]);

        $imagePath = $request->hasFile('prd_card_url')
            ? $request->file('prd_card_url')->store('products', 'public')
            : null;

        Product::create([
            'prd_name'        => $validated['prd_name'],
            'prd_description' => $validated['prd_description'],
            'prd_price'       => $validated['prd_price'],
            'prd_status'      => $validated['prd_status'],
            'prd_card_url'    => $imagePath,
            'lcn_id'          => $validated['lcn_id'],
            'prd_created_by'  => Auth::id(),
        ]);

        return redirect()->route('posts.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Show detail product
     */
    public function show($id)
    {
        $product = Product::with('location')->findOrFail($id);
        return view('posts.show', compact('product'));
    }

    /**
     * Edit product
     */
    public function edit($id)
    {
        $product   = Product::findOrFail($id);
        $locations = Location::all();

        return view('posts.edit', compact('product', 'locations'));
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'prd_name'        => 'required|string',
            'prd_description' => 'required|string',
            'prd_price'       => 'required|integer',
            'prd_status'      => 'required|in:tersedia,tidak tersedia',
            'prd_card_url'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'lcn_id'          => 'required|exists:locations,lcn_id',
        ]);

        $imagePath = $product->prd_card_url;

        if ($request->hasFile('prd_card_url')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('prd_card_url')->store('products', 'public');
        }

        $product->update([
            'prd_name'        => $validated['prd_name'],
            'prd_description' => $validated['prd_description'],
            'prd_price'       => $validated['prd_price'],
            'prd_status'      => $validated['prd_status'],
            'prd_card_url'    => $imagePath,
            'lcn_id'          => $validated['lcn_id'],
            'prd_updated_by'  => Auth::id(),
        ]);

        return redirect()->route('posts.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'prd_deleted_by' => Auth::id(),
        ]);

        $product->delete();

        return redirect()->route('posts.index')->with('success', 'Produk berhasil dihapus!');
    }
}

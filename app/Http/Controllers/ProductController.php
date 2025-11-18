<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    /* ================================
     * USER — LIST PRODUK (Landing Page)
     * ================================ */
    public function index()
    {
        $products = Product::latest()->paginate(12);
        return view('livewire.user.landing', compact('products'));
    }



    /* ================================
     * ADMIN — LIST PRODUK (Kelola)
     * ================================ */
    public function adminIndex()
    {
        $products = Product::latest()->paginate(12);
        return view('livewire.admin.products.index', compact('products'));
    }



    /* ================================
     * FORM TAMBAH PRODUK
     * ================================ */
    public function create()
    {
        return view('livewire.admin.products.create');
    }



    /* ================================
     * SIMPAN PRODUK BARU
     * ================================ */
    public function store(Request $request)
    {
        $request->validate([
            'prd_name' => 'required|string|max:255',
            'prd_description' => 'required|string',
            'prd_status' => 'required|in:tersedia,tidak tersedia',
            'prd_price' => 'required|integer|min:0',
            'prd_card_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $request->hasFile('prd_card_url')
            ? 'storage/' . $request->file('prd_card_url')->store('products', 'public')
            : null;

        Product::create([
            'prd_name' => $request->prd_name,
            'prd_description' => $request->prd_description,
            'prd_status' => $request->prd_status,
            'prd_price' => $request->prd_price,
            'prd_card_url' => $imagePath,
            'prd_created_by' => Auth::id(),
        ]);

        return redirect()->route('products.admin.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }



    /* ================================
     * DETAIL PRODUK
     * ================================ */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('livewire.admin.products.show', compact('product'));
    }



    /* ================================
     * FORM EDIT PRODUK
     * ================================ */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('livewire.admin.products.edit', compact('product'));
    }



    /* ================================
     * UPDATE PRODUK
     * ================================ */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'prd_name' => 'required|string|max:255',
            'prd_description' => 'required|string',
            'prd_status' => 'required|in:tersedia,tidak tersedia',
            'prd_price' => 'required|integer|min:0',
            'prd_card_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $product->prd_card_url;

        if ($request->hasFile('prd_card_url')) {
            $imagePath = 'storage/' . $request->file('prd_card_url')->store('products', 'public');
        }

        $product->update([
            'prd_name' => $request->prd_name,
            'prd_description' => $request->prd_description,
            'prd_status' => $request->prd_status,
            'prd_price' => $request->prd_price,
            'prd_card_url' => $imagePath,
        ]);

        return redirect()->route('products.admin.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }



    /* ================================
     * SOFT DELETE — PINDAH KE TRASH
     * ================================ */
    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $product->delete(); // Soft delete

        return back()->with('success', 'Produk berhasil dihapus.');
    }



    /* ================================
     * LIST PRODUK TERHAPUS
     * ================================ */
    public function trashed()
    {
        $products = Product::onlyTrashed()->latest()->paginate(12);
        return view('livewire.admin.products.trash', compact('products'));
    }



    /* ================================
     * RESTORE PRODUK
     * ================================ */
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('products.trashed')
            ->with('success', 'Produk berhasil direstore.');
    }



    /* ================================
     * HAPUS PERMANEN (FORCE DELETE)
     * ================================ */
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->forceDelete();

        return redirect()->route('products.trashed')
            ->with('success', 'Produk dihapus permanen.');
    }
}

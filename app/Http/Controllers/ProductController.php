<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    /* ============================================================
     * USER — LANDING PAGE (List Produk untuk Pembeli)
     * ============================================================ */
    public function index()
    {
        $products = Product::where('prd_status', 'tersedia')
            ->latest()
            ->paginate(12);

        return view('livewire.user.landing', [
            'products' => $products,
            'title' => 'Daftar Produk',
        ]);
    }



    /* ============================================================
     * ADMIN — LIST PRODUK (Kelola Produk)
     * ============================================================ */
    public function adminIndex()
    {
        $products = Product::latest()->paginate(12);

        return view('livewire.admin.products.index', [
            'products' => $products,
            'title' => 'Kelola Produk',
        ]);
    }



    /* ============================================================
     * FORM TAMBAH PRODUK (ADMIN)
     * ============================================================ */
    public function create()
    {
        return view('livewire.admin.products.create', [
            'title' => 'Tambah Produk Baru',
        ]);
    }



    /* ============================================================
     * SIMPAN PRODUK BARU (ADMIN)
     * ============================================================ */
    public function store(Request $request)
    {
        $request->validate([
            'prd_name'        => 'required|string|max:255',
            'prd_description' => 'required|string',
            'prd_status'      => 'required|in:tersedia,tidak tersedia',
            'prd_price'       => 'required|integer|min:0',
            'prd_card_url'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $request->hasFile('prd_card_url')
            ? 'storage/' . $request->file('prd_card_url')->store('products', 'public')
            : null;

        Product::create([
            'prd_name'        => $request->prd_name,
            'prd_description' => $request->prd_description,
            'prd_status'      => $request->prd_status,
            'prd_price'       => $request->prd_price,
            'prd_card_url'    => $imagePath,
            'prd_created_by'  => Auth::id(),
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }



    /* ============================================================
     * DETAIL PRODUK (ADMIN)
     * ============================================================ */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('livewire.admin.products.show', [
            'product' => $product,
            'title' => 'Detail Produk: ' . $product->prd_name,
        ]);
    }



    /* ============================================================
     * FORM EDIT PRODUK (ADMIN)
     * ============================================================ */
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('livewire.admin.products.edit', [
            'product' => $product,
            'title' => 'Edit Produk: ' . $product->prd_name,
        ]);
    }



    /* ============================================================
     * UPDATE PRODUK (ADMIN)
     * ============================================================ */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'prd_name'        => 'required|string|max:255',
            'prd_description' => 'required|string',
            'prd_status'      => 'required|in:tersedia,tidak tersedia',
            'prd_price'       => 'required|integer|min:0',
            'prd_card_url'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $request->hasFile('prd_card_url')
            ? 'storage/' . $request->file('prd_card_url')->store('products', 'public')
            : $product->prd_card_url;

        $product->update([
            'prd_name'        => $request->prd_name,
            'prd_description' => $request->prd_description,
            'prd_status'      => $request->prd_status,
            'prd_price'       => $request->prd_price,
            'prd_card_url'    => $imagePath,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }



    /* ============================================================
     * SOFT DELETE — PINDAH KE TRASH
     * ============================================================ */
    public function delete($id)
    {
        Product::findOrFail($id)->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }



    /* ============================================================
     * LIST PRODUK TERHAPUS (ADMIN)
     * ============================================================ */
    public function trashed()
    {
        $products = Product::onlyTrashed()->latest()->paginate(12);

        return view('livewire.admin.products.trash', [
            'products' => $products,
            'title' => 'Produk Terhapus',
        ]);
    }



    /* ============================================================
     * RESTORE PRODUK (ADMIN)
     * ============================================================ */
    public function restore($id)
    {
        Product::onlyTrashed()->findOrFail($id)->restore();

        return redirect()
            ->route('products.trashed')
            ->with('success', 'Produk berhasil direstore.');
    }



    /* ============================================================
     * FORCE DELETE — HAPUS PERMANEN
     * ============================================================ */
    public function forceDelete($id)
    {
        Product::onlyTrashed()->findOrFail($id)->forceDelete();

        return redirect()
            ->route('products.trashed')
            ->with('success', 'Produk dihapus permanen.');
    }
}

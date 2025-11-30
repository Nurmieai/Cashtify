<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->hasRole('Penjual')) {
            return redirect()->route('dashboard');
        }

        $products = Product::where('prd_status', 'tersedia')
            ->latest('usr_created_at')
            ->paginate(12);

        return view('livewire.user.landing', [
            'products' => $products,
            'title'    => 'Daftar Produk',
        ]);
    }

    public function adminIndex(Request $request)
    {
        // Ambil keyword
        $search = $request->input('search');

        // Base query
        $query = Product::query()->latest('usr_created_at');

        // Kalau ada keyword, filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('prd_name', 'like', "%{$search}%")
                ->orWhere('prd_description', 'like', "%{$search}%")
                ->orWhere('prd_price', 'like', "%{$search}%");
            });
        }

        // Eksekusi pagination
        $products = $query->paginate(12)->withQueryString();

        return view('livewire.admin.products.index', [
            'products' => $products,
            'title'    => 'Kelola Produk',
        ]);
    }


    public function create()
    {
        return view('livewire.admin.products.create', [
            'title' => 'Tambah Produk Baru',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'prd_name'        => 'required|string|max:255|unique:products,prd_name',
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
            ->route('products.adminIndex')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show($id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        return view('livewire.admin.products.detail', [
            'product' => $product,
            'title'   => 'Detail Produk: ' . $product->prd_name,
        ]);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('livewire.admin.products.edit', [
            'product' => $product,
            'title'   => 'Edit Produk: ' . $product->prd_name,
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'prd_name'        => 'required|string|max:255|unique:products,prd_name,' . $product->prd_id . ',prd_id',
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
            'prd_updated_by'  => Auth::id(),
        ]);

        return redirect()
            ->route('products.adminIndex')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.trashed')
                 ->with('success', 'Produk dipindahkan ke sampah!');
    }


    public function trashed()
    {
        $products = Product::onlyTrashed()
            ->latest('usr_deleted_at')
            ->paginate(12);

        return view('livewire.admin.products.trash', [
            'products' => $products,
            'title'    => 'Produk Terhapus',
        ]);
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        $product->prd_deleted_by = null;
        $product->save();
        $product->restore();

        return redirect()
            ->route('products.trashed')
            ->with('success', 'Produk berhasil direstore.');
    }

    public function forceDelete($id)
    {
        Product::onlyTrashed()->findOrFail($id)->forceDelete();

        return redirect()
            ->route('products.trashed')
            ->with('success', 'Produk dihapus permanen.');
    }
}

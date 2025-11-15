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

        $products = Product::latest()->paginate(10);
        return view('livewire.user.landing', compact('products'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'prd_name' => 'required|string|max:255',
            'prd_description' => 'required|string',
            'prd_status' => 'required|in:tersedia,tidak tersedia',
            'prd_price' => 'required|integer',
            'prd_card_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('prd_card_url')) {
            $imagePath = $request->file('prd_card_url')->store('products', 'public');
        }

        Product::create([
            'prd_name' => $request->prd_name,
            'prd_description' => $request->prd_description,
            'prd_status' => $request->prd_status,
            'prd_price' => $request->prd_price,
            'prd_card_url' => $imagePath ? 'storage/' . $imagePath : null,
            'prd_created_by' => Auth::id(),
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

}

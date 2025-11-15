<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Post;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function dashboard()
    {
         return view('livewire.admin.dashboard', [
        'locationsCount' => Location::count(),

        'postsCount' => Post::count(),
        'latestPosts' => Post::with('createdBy')
                             ->latest()
                             ->take(2)
                             ->get(),

        'productsCount' => Product::count(),

        'buyersCount'  => User::role('Pembeli')->count(),
        'sellersCount' => User::role('Penjual')->count(),

        'transactionsCount' => Transaction::count(),

        'monthlyIncome' => Transaction::whereMonth('trx_created_at', Carbon::now()->month)
                                      ->whereYear('trx_created_at', Carbon::now()->year)
                                      ->where('trx_status', 'paid')
                                      ->sum('trx_total'),

        'todayIncome' => Transaction::whereDate('trx_created_at', now()->toDateString())
                            ->where('trx_status', 'paid')
                            ->sum('trx_total'),

        'latestTransactions' => Transaction::with(['buyer', 'seller'])
                                           ->latest()
                                           ->take(5)
                                           ->get(),
    ]);
    }
}

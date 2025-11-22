<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function dashboard()
    {
        $buyersCount       = User::whereHas('roles', fn($q) => $q->where('name', 'Pembeli'))->count();
        $productsCount     = Product::count();
        $transactionsCount = Transaction::count();

        // statistik 30 hari terakhir
        $startDate = Carbon::now()->subDays(30);
        $rawStats  = Transaction::where('tst_payment_status', 2)
            ->where('tst_created_at', '>=', $startDate)
            ->selectRaw('DATE(tst_created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $productStats = [];
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays(29 - $i)->toDateString();
            $productStats[$date] = $rawStats[$date] ?? 0;
        }

        $tstStatus = [
            'pending'   => Transaction::where('tst_status', 1)->count(),
            'paid'      => Transaction::where('tst_status', 2)->count(),
            'shipping'  => Transaction::where('tst_status', 4)->count(),
            'complete'  => Transaction::where('tst_status', 5)->count(),
            'cancelled' => Transaction::where('tst_status', 6)->count(),
        ];

        $latestTransactions = Transaction::with('buyer')
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.admin.dashboard', compact(
            'buyersCount',
            'productsCount',
            'transactionsCount',
            'productStats',
            'tstStatus',
            'latestTransactions'
        ));
    }
}

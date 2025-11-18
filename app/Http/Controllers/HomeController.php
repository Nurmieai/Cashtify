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
        // --- Statistik Utama ---
        $buyersCount = User::whereHas('roles', fn($q) => $q->where('name', 'Pembeli'))->count();

        $productsCount = Product::count();

        $transactionsCount = Transaction::count();

        // --- Produk terjual 30 hari terakhir (dummy: sesuaikan dengan tabelmu) ---
        $startDate = Carbon::now()->subDays(30);

        $productStatsRaw = Transaction::where('tst_status', 'paid')
            ->where('tst_created_at', '>=', $startDate)
            ->selectRaw('DATE(tst_created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Isi tanggal yang kosong
        $productStats = [];
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays(29 - $i)->toDateString();
            $productStats[$date] = $productStatsRaw[$date] ?? 0;
        }

        // --- Status Transaksi ---
        $tstStatus = [
            'paid'      => Transaction::where('tst_status', 'paid')->count(),
            'pending'   => Transaction::where('tst_status', 'pending')->count(),
            'failed'    => Transaction::where('tst_status', 'failed')->count(),
            'expired'   => Transaction::where('tst_status', 'expired')->count(),
            'cancelled' => Transaction::where('tst_status', 'cancelled')->count(),
        ];

        // --- Transaksi terbaru ---
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

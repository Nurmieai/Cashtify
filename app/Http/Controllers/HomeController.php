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
        // ========== COUNT DATA ==========
        $buyersCount = User::count();
        $productsCount     = Product::count();
        $transactionsCount = Transaction::count();


        // ========== PRODUK TERJUAL 30 HARI (BAR CHART) ==========
        $productStats = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();

            $count = Transaction::whereDate('tst_created_at', $date)
                ->where('tst_status', 'paid')
                ->sum('tst_total'); // tanpa transaction items → pakai total transaksi

            $productStats[$date] = (int) $count;
        }


        // ========== STATUS TRANSAKSI (DONUT CHART) ==========
        $tstStatus = [
            'paid'      => Transaction::where('tst_status', 'paid')->count(),
            'pending'   => Transaction::where('tst_status', 'pending')->count(),
            'failed'    => Transaction::where('tst_status', 'failed')->count(),
            'expired'   => Transaction::where('tst_status', 'expired')->count(),
            'cancelled' => Transaction::where('tst_status', 'cancelled')->count(),
        ];


        // ========== TRANSAKSI TERBARU ==========
        $latestTransactions = Transaction::with(['buyer'])
            ->orderBy('tst_created_at', 'desc')
            ->take(5)
            ->get();


        // ========== FINAL SEND TO BLADE ==========
        return view('livewire.admin.dashboard', [
            'buyersCount'       => $buyersCount,
            'productsCount'     => $productsCount,
            'transactionsCount' => $transactionsCount,

            'productStats'      => $productStats,
            'tstStatus'         => $tstStatus,
            'latestTransactions'=> $latestTransactions,
        ]);
    }
}

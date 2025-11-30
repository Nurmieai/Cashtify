<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem; // Diperlukan untuk Chart Produk Terjual
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Diperlukan untuk selectRaw dan SUM

class HomeController extends Controller
{
    public function dashboard()
    {
        // Mendefinisikan batas waktu 30 hari yang lalu
        $thirtyDaysAgo = Carbon::now()->subDays(30)->startOfDay();

        // 1. STATISTIK KARTU (Counts)
        $buyersCount       = User::whereHas('roles', fn($q) => $q->where('name', 'Pembeli'))->count();
        $productsCount     = Product::count();
        $transactionsCount = Transaction::count();

        // 2. CHART BAR: PRODUK TERJUAL 30 HARI TERAKHIR
        // Menggunakan TransactionItem untuk menghitung kuantitas produk yang terjual
        $rawStats = TransactionItem::where('tst_item_created_at', '>=', $thirtyDaysAgo)
            // Filter hanya transaksi yang sudah dibayar (asumsi: pembayaran sukses)
            ->whereHas('transaction', fn($q) => $q->where('tst_payment_status', 'paid')) 
            ->select(
                DB::raw('DATE(tst_item_created_at) as date'), 
                DB::raw('SUM(tst_item_quantity) as total') // SUM kuantitas produk
            )
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Mengisi tanggal kosong dengan 0 dan mengubah format key menjadi 'j M' untuk label Chart
        $productStats = [];
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays(29 - $i)->toDateString();
            $label = Carbon::parse($date)->format('j M'); 
            $productStats[$label] = $rawStats[$date] ?? 0;
        }

        // 3. CHART DOUGHNUT: STATUS TRANSAKSI
        // Menggunakan status STRING ENUM yang benar (berdasarkan TransactionController terbaru)
        $tstStatus = [
            'pending'   => Transaction::where('tst_status', 'pending')->count(),   // Status 1
            'paid'      => Transaction::where('tst_status', 'paid')->count(),      // Status 2
            'verified'  => Transaction::where('tst_status', 'verified')->count(),  // Status 3 (Menunggu Konfirmasi Penjual)
            'sent'      => Transaction::where('tst_status', 'sent')->count(),      // Status 4 (Sedang Dikirim)
            'done'      => Transaction::where('tst_status', 'done')->count(),      // Status 5 (Selesai)
            'cancelled' => Transaction::where('tst_status', 'cancelled')->count(),  // Status 6 (Dibatalkan)
            // Anda bisa menambahkan 'waiting', 'failed', 'expired' jika ada di ENUM Anda
        ];
        
        // Hapus status yang count-nya nol agar chart tidak kosong
        $tstStatus = array_filter($tstStatus, fn($count) => $count > 0);


        // 4. TABEL TRANSAKSI TERBARU
        $latestTransactions = Transaction::with('buyer')
            ->latest('tst_created_at')
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
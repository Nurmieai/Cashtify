<?php

namespace App\Http\Controllers;

use App\Models\Accounting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AccountingController extends Controller
{
    /**
     * Halaman daftar laporan (with filter)
     */
    public function index(Request $request)
    {
        $query = Accounting::query();

        // --- FILTER ---
        $filter = $request->filter;

        if ($filter === 'daily') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filter === 'weekly') {
            $query->whereBetween('created_at', [
                Carbon::today()->startOfWeek(),
                Carbon::today()->endOfWeek(),
            ]);
        } elseif ($filter === 'monthly') {
            $query->whereYear('created_at', now()->year)
                  ->whereMonth('created_at', now()->month);
        } elseif ($filter === 'range' && $request->from && $request->to) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $accountings = $query->orderByDesc('act_id')->paginate(10)->withQueryString();

        return view('livewire.admin.accounting.index', compact('accountings'));
    }

    /**
     * Form membuat laporan
     */
    public function create()
    {
        return view('admin.accounting.create');
    }

    /**
     * Simpan laporan akuntansi
     */
    public function store(Request $request)
    {
        $request->validate([
            'act_period_from' => 'required|date',
            'act_period_to'   => 'required|date|after_or_equal:act_period_from',
        ]);

        $transactions = Transaction::whereBetween('tst_created_at', [
            $request->act_period_from,
            $request->act_period_to,
        ])->where('tst_payment_status', 'paid')->get();

        $totalItems = 0;
        foreach ($transactions as $trx) {
            if ($trx->items_json) {
                foreach (json_decode($trx->items_json, true) as $item) {
                    $totalItems += $item['quantity'] ?? 0;
                }
            }
        }

        Accounting::create([
            'act_user_id'              => Auth::id(),
            'act_period_from'          => $request->act_period_from,
            'act_period_to'            => $request->act_period_to,
            'act_total_transactions'   => $transactions->count(),
            'act_transaction_ids'      => $transactions->pluck('tst_id')->toArray(),
            'act_total_sales'          => $transactions->sum('tst_subtotal'),
            'act_total_items_sold'     => $totalItems,
            'act_total_payment_amount' => $transactions->sum('tst_payment_amount'),
            'act_total_shipping_cost'  => $transactions->sum('tst_shipping_cost'),
            'act_total_income'         => $transactions->sum('tst_payment_amount'),
            'act_total_expense'        => 0,
            'act_created_by'           => Auth::id(),
        ]);

        return redirect()->route('accounting.index')
            ->with('success', 'Laporan akuntansi berhasil dibuat.');
    }

    /**
     * Export PDF untuk SATU laporan
     */
    public function exportSingle($id)
    {
        $accounting = Accounting::findOrFail($id);

        $pdf = Pdf::loadView('admin.accounting.pdf-single', [
            'acc' => $accounting
        ])->setPaper('A4', 'portrait');

        return $pdf->download("laporan-akuntansi-{$id}.pdf");
    }

    /**
     * Export PDF untuk HASIL FILTER (bulk)
     */
    public function print(Request $request)
    {
        $query = Accounting::query();

        // SAME FILTER AS INDEX
        if ($request->filter === 'daily') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($request->filter === 'weekly') {
            $query->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ]);
        } elseif ($request->filter === 'monthly') {
            $query->whereYear('created_at', now()->year)
                  ->whereMonth('created_at', now()->month);
        } elseif ($request->from && $request->to) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $accountings = $query->orderBy('act_period_from')->get();

        $range = strtoupper(
            $request->filter === 'daily'  ? 'HARI INI' :
            ($request->filter === 'weekly' ? 'MINGGU INI' :
            ($request->filter === 'monthly' ? 'BULAN INI' :
            (($request->from && $request->to) ? "{$request->from} s/d {$request->to}" : 'SEMUA')))
        );

        $pdf = Pdf::loadView('admin.accounting.pdf', [
            'accountings' => $accountings,
            'range'       => $range,
        ])->setPaper('A4', 'landscape');

        return $pdf->download("laporan-akuntansi-filter.pdf");
    }
}

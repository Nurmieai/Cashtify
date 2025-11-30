<?php

namespace App\Http\Controllers;

use App\Models\Accounting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AccountingController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->range ?? 'daily';

        $query = Accounting::query();

        // Filter berdasarkan range
        if ($range === 'daily') {
            $query->whereDate('act_period_from', today());
        } elseif ($range === 'weekly') {
            $query->whereBetween('act_period_from', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($range === 'monthly') {
            $query->whereMonth('act_period_from', now()->month)
                  ->whereYear('act_period_from', now()->year);
        }

        $accountings = $query->orderBy('act_period_from', 'desc')->paginate(10)->withQueryString();

        return view('livewire.admin.accounting.index', compact('accountings', 'range'));
    }

    public function print(Request $request)
    {
        $range = $request->range ?? 'daily';

        $query = Accounting::query();

        if ($range === 'daily') {
            $query->whereDate('act_period_from', today());
        } elseif ($range === 'weekly') {
            $query->whereBetween('act_period_from', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($range === 'monthly') {
            $query->whereMonth('act_period_from', now()->month)
                  ->whereYear('act_period_from', now()->year);
        }

        $accountings = $query->get();

        $pdf = Pdf::loadView('livewire.admin.accounting.pdf', [
            'accountings' => $accountings,
            'range' => $range,
        ]);

        return $pdf->download("laporan-accounting-{$range}.pdf");
    }
}

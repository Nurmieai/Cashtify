<x-layouts.admin.main>
    <x-slot:title>Laporan Accounting</x-slot:title>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Laporan Accounting</h4>

        <form method="GET" action="{{ route('accounting.index') }}" class="d-flex gap-2">
            <select name="range" class="form-select" onchange="this.form.submit()">
                <option value="daily" {{ $range === 'daily' ? 'selected' : '' }}>Harian</option>
                <option value="weekly" {{ $range === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                <option value="monthly" {{ $range === 'monthly' ? 'selected' : '' }}>Bulanan</option>
            </select>
        </form>

        <a href="{{ route('accounting.print', ['range' => $range]) }}" class="btn btn-danger">
            <i class="bi bi-printer"></i> Print PDF
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Periode</th>
                    <th>Total Penjualan</th>
                    <th>Total Item Terjual</th>
                    <th>Total Pengiriman</th>
                    <th>Pendapatan Total</th>
                    <th>Pengeluaran Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($accountings as $acc)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $acc->act_period_from }} → {{ $acc->act_period_to }}</td>
                        <td>Rp{{ number_format($acc->act_total_sales, 0, ',', '.') }}</td>
                        <td>{{ $acc->act_total_items_sold }}</td>
                        <td>{{ $acc->act_total_shipments }}</td>
                        <td>Rp{{ number_format($acc->act_total_income, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($acc->act_total_expense, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada data…</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $accountings->links() }}
        </div>
    </div>

</x-layouts.admin.main>

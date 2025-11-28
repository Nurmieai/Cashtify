<x-layouts.admin.main>
    <x-slot:title>Laporan Akuntansi</x-slot:title>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Laporan Akuntansi</h4>
            <small class="text-muted">Tampilkan laporan berdasarkan rentang waktu</small>
        </div>

        <div class="d-flex gap-2 align-items-center">

            <!-- FILTER -->
            <form method="GET" action="{{ route('accounting.index') }}" class="d-flex gap-2 align-items-center">
                <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    <option value="daily"  {{ request('filter') === 'daily' ? 'selected' : '' }}>Harian</option>
                    <option value="weekly" {{ request('filter') === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                    <option value="monthly"{{ request('filter') === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="range"  {{ request('filter') === 'range' ? 'selected' : '' }}>Rentang</option>
                </select>

                <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
                <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">

                <button class="btn btn-primary btn-sm">Terapkan</button>
            </form>

            <a href="{{ route('accounting.create') }}" class="btn btn-success btn-sm">
                + Buat Laporan Baru
            </a>

            <a href="{{ route('accounting.print', array_filter([
                    'filter' => request('filter'),
                    'from'   => request('from'),
                    'to'     => request('to')
                ])) }}" class="btn btn-danger btn-sm">
                <i class="bi bi-printer"></i> Cetak PDF
            </a>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Periode</th>
                            <th>Jumlah Transaksi</th>
                            <th>Total Penjualan</th>
                            <th>Item Terjual</th>
                            <th>Pembayaran Masuk</th>
                            <th>Total Ongkir</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($accountings as $acc)
                            <tr>
                                <td>{{ $loop->iteration + ($accountings->currentPage() - 1) * $accountings->perPage() }}</td>

                                <td>{{ $acc->act_period_from }} s/d {{ $acc->act_period_to }}</td>

                                <td>{{ $acc->act_total_transactions }}</td>

                                <td>Rp {{ number_format($acc->act_total_sales, 0, ',', '.') }}</td>

                                <td>{{ $acc->act_total_items_sold }}</td>

                                <td>Rp {{ number_format($acc->act_total_payment_amount, 0, ',', '.') }}</td>

                                <td>Rp {{ number_format($acc->act_total_shipping_cost, 0, ',', '.') }}</td>

                                <td>{{ $acc->user?->name ?? '—' }}</td>

                                <td>
                                    <a href="{{ route('accounting.exportPdf', $acc->act_id) }}"
                                       class="btn btn-sm btn-danger">
                                        PDF
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    Tidak ada data untuk periode ini...
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>

        <div class="card-footer">
            {{ $accountings->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>

</x-layouts.admin.main>

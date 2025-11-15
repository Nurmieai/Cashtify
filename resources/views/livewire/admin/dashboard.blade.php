<x-layouts.main>
    <x-slot name="title">Dashboard Admin</x-slot>

    <div class="container py-4">

        {{-- Statistik Ringkas --}}
        <div class="row g-4 mb-4">

            {{-- Total Produk --}}
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Produk</h6>
                        <h3 class="fw-bold">{{ $productsCount }}</h3>
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary mt-2">Lihat</a>
                    </div>
                </div>
            </div>

            {{-- Total Transaksi --}}
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Transaksi</h6>
                        <h3 class="fw-bold">{{ $transactionsCount }}</h3>
                        <a href="{{ route('orders') }}" class="btn btn-sm btn-outline-success mt-2">Lihat</a>
                    </div>
                </div>
            </div>

            {{-- Total Pembeli --}}
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Pembeli</h6>
                        <h3 class="fw-bold">{{ $buyersCount }}</h3>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-info mt-2">Lihat</a>
                    </div>
                </div>
            </div>

            {{-- Pemasukan Hari Ini --}}
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Pemasukan Hari Ini</h6>
                        <h3 class="fw-bold">
                            Rp {{ number_format($todayIncome, 0, ',', '.') }}
                        </h3>
                        <a href="{{ route('orders') }}" class="btn btn-sm btn-outline-dark mt-2">Detail</a>
                    </div>
                </div>
            </div>

        </div>

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body">
                <h5 class="card-title mb-3">Transaksi Terbaru</h5>

                <table class="table table-striped table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Invoice</th>
                            <th>Pembeli</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($latestTransactions as $index => $trx)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                {{-- invoice --}}
                                <td>{{ $trx->tst_invoice }}</td>

                                {{-- nama buyer --}}
                                <td>{{ $trx->buyer->usr_name ?? 'Guest' }}</td>

                                {{-- total --}}
                                <td>Rp {{ number_format($trx->tst_total, 0, ',', '.') }}</td>

                                {{-- status badge --}}
                                <td>
                                    <span class="badge
                                        @if($trx->tst_status == 'paid') bg-success
                                        @elseif($trx->tst_status == 'pending') bg-warning
                                        @else bg-danger
                                        @endif">
                                        {{ ucfirst($trx->tst_status) }}
                                    </span>
                                </td>

                                {{-- tanggal --}}
                                <td>{{ optional($trx->tst_created_at)->format('Y-m-d') }}</td>

                                {{-- detail --}}
                                <td>
                                    <a href="{{ route('orders.show', $trx->tst_id) }}"
                                       class="btn btn-sm btn-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada transaksi</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>

            </div>
        </div>

    </div>

    <footer class="bg-white border-top py-3 mt-auto">
        <div class="container text-center">
            <small class="text-muted">&copy; 2025 Cashtify. All rights reserved.</small>
        </div>
    </footer>

</x-layouts.main>

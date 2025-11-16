<x-layouts.admin.main>
    <x-slot name="title">Dashboard Admin</x-slot>

    <div class="container py-4">

        {{-- ====== KARTU STATISTIK UTAMA ====== --}}
        <div class="row g-4 mb-4">

            <div class="col-md-4">
                <div class="card border-0 text-white" style="background:#527edb;">
                    <div class="card-body">
                        <h2 class="fw-bold">{{ $buyersCount }}</h2>
                        <p class="mb-0">Pengguna Aktif</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 text-white" style="background:#f2b631;">
                    <div class="card-body">
                        <h2 class="fw-bold">{{ $productsCount }}</h2>
                        <p class="mb-0">Total Produk</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 text-white" style="background:#42c6b1;">
                    <div class="card-body">
                        <h2 class="fw-bold">{{ $transactionsCount }}</h2>
                        <p class="mb-0">Total Transaksi</p>
                    </div>
                </div>
            </div>

        </div>


        {{-- ====== BAGIAN CHART ====== --}}
        <div class="row mb-4">

            {{-- BAR CHART PRODUK TERJUAL --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold">Produk Terjual 30 Hari Terakhir</h5>
                        <canvas id="productChart" height="140"></canvas>
                    </div>

                    <div class="p-3 bg-light">
                        <p class="mb-1">Periode: 30 hari terakhir</p>
                        <p class="mb-1">Total Produk Terjual: <strong>{{ array_sum($productStats) }}</strong></p>
                        <p class="mb-0">Rata-rata / hari: <strong>{{ number_format(array_sum($productStats) / 30, 1) }}</strong></p>
                    </div>
                </div>
            </div>

            {{-- DONUT CHART STATUS TRANSAKSI --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold text-center">Status Transaksi</h5>
                        <canvas id="statusChart" height="260"></canvas>
                    </div>
                </div>
            </div>

        </div>


        {{-- ====== TABEL TRANSAKSI ====== --}}
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body">
                <h5 class="card-title mb-3 fw-bold">Transaksi Terbaru</h5>

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
                        @forelse($latestTransactions as $index => $tst)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $tst->tst_invoice }}</td>
                                <td>{{ $tst->buyer->usr_name ?? 'Guest' }}</td>
                                <td>Rp {{ number_format($tst->tst_total, 0, ',', '.') }}</td>

                                <td>
                                    <span class="badge
                                        @if($tst->tst_status === 'paid') bg-success
                                        @elseif($tst->tst_status === 'pending') bg-warning
                                        @elseif($tst->tst_status === 'failed') bg-danger
                                        @elseif($tst->tst_status === 'expired') bg-secondary
                                        @elseif($tst->tst_status === 'cancelled') bg-dark
                                        @endif">
                                        {{ ucfirst($tst->tst_status) }}
                                    </span>
                                </td>

                                <td>{{ $tst->tst_created_at?->format('Y-m-d H:i') }}</td>

                                <td>
                                    <a href="{{ route('orders.show', $tst->tst_id) }}" class="btn btn-sm btn-primary">
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


    {{-- ================= CHART.JS ================= --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // ================= BAR CHART PRODUK TERJUAL =================
        new Chart(document.getElementById('productChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($productStats)) !!},
                datasets: [{
                    label: "Produk Terjual",
                    data: {!! json_encode(array_values($productStats)) !!},
                    backgroundColor: "#2d8be8"
                }]
            },
            options: {
                scales: { y: { beginAtZero: true } }
            }
        });

        // ================= DONUT CHART TRANSAKSI =================
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ["Paid", "Pending", "Failed", "Expired", "Cancelled"],
                datasets: [{
                    data: [
                        {{ $tstStatus['paid'] ?? 0 }},
                        {{ $tstStatus['pending'] ?? 0 }},
                        {{ $tstStatus['failed'] ?? 0 }},
                        {{ $tstStatus['expired'] ?? 0 }},
                        {{ $tstStatus['cancelled'] ?? 0 }}
                    ],
                    backgroundColor: ['#4caf50', '#ffca28', '#f44336', '#9e9e9e', '#424242']
                }]
            },
            options: {
                cutout: '70%'
            }
        });
    </script>

</x-layouts.admin.main>

<x-layouts.admin.main>
    <x-slot name="title">Dashboard Admin</x-slot>

    <div class="container py-4">

        {{-- ================== HEADER ================== --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Dashboard</h3>
            <span class="text-muted">Update terakhir: {{ now()->format('d M Y H:i') }}</span>
        </div>

        {{-- ================== KARTU STATISTIK ================== --}}
        <div class="row g-4 mb-4">

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Pengguna Aktif</p>
                            <h3 class="fw-bold">{{ $buyersCount }}</h3>
                        </div>
                        <i class="bi bi-people-fill fs-2 text-primary"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Produk</p>
                            <h3 class="fw-bold">{{ $productsCount }}</h3>
                        </div>
                        <i class="bi bi-box-seam fs-2 text-success"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Transaksi</p>
                            <h3 class="fw-bold">{{ $transactionsCount }}</h3>
                        </div>
                        <i class="bi bi-cash-stack fs-2 text-warning"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- ================== CHARTS ================== --}}
        <div class="row mb-4">

            {{-- BAR --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Produk Terjual 30 Hari Terakhir</h5>
                        <canvas id="productChart" height="140"></canvas>
                    </div>

                    <div class="p-3 bg-light border-top">
                        <small class="text-muted">Periode: 30 hari terakhir</small><br>
                        <small>Total: <strong>{{ array_sum($productStats) }}</strong></small> |
                        <small>Rata-rata: <strong>{{ number_format(array_sum($productStats)/30,1) }}</strong> / hari</small>
                    </div>
                </div>
            </div>

            {{-- DOUGHNUT --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3 text-center">Status Transaksi</h5>
                        <canvas id="statusChart" height="250"></canvas>
                    </div>
                </div>
            </div>

        </div>


        {{-- ================== TABEL TRANSAKSI ================== --}}
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body">

                <h5 class="card-title mb-3 fw-bold">Transaksi Terbaru</h5>

                <table class="table table-hover align-middle">
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
                                <td class="text-muted">{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $tst->tst_invoice }}</td>
                                <td>{{ $tst->buyer->usr_name ?? 'Guest' }}</td>

                                <td>Rp {{ number_format($tst->tst_total, 0, ',', '.') }}</td>

                                <td>
                                    <span class="badge rounded-pill
                                        @if($tst->tst_status === 'paid') bg-success
                                        @elseif($tst->tst_status === 'pending') bg-warning
                                        @elseif($tst->tst_status === 'failed') bg-danger
                                        @elseif($tst->tst_status === 'expired') bg-secondary
                                        @elseif($tst->tst_status === 'cancelled') bg-dark
                                        @endif">
                                        {{ ucfirst($tst->tst_status) }}
                                    </span>
                                </td>

                                <td>{{ optional($tst->tst_created_at)->format('d M Y H:i') }}</td>

                                <td>
                                    <a href="{{ route('orders.show', $tst->tst_id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Tidak ada transaksi terbaru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>

    </div>


    {{-- ================== FOOTER ================== --}}
    <footer class="bg-white border-top py-3 mt-auto">
        <div class="container text-center">
            <small class="text-muted">&copy; 2025 Cashtify. All rights reserved.</small>
        </div>
    </footer>

    {{-- ================== CHART SCRIPT ================== --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // BAR CHART
        new Chart(document.getElementById('productChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($productStats)) !!},
                datasets: [{
                    label: "Produk Terjual",
                    data: {!! json_encode(array_values($productStats)) !!},
                    backgroundColor: "rgba(54, 162, 235, 0.6)"
                }]
            },
            options: {
                scales: { y: { beginAtZero: true } }
            }
        });

        // DOUGHNUT CHART
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
                    backgroundColor: [
                        "#4CAF50", "#FFC107", "#F44336", "#9E9E9E", "#424242"
                    ]
                }]
            },
            options: { cutout: '70%' }
        });
    </script>

</x-layouts.admin.main>

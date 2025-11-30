<x-layouts.admin.main>
    <x-slot name="title">Dashboard Admin</x-slot>

    <div class="container py-4">

        {{-- ================== HEADER ================== --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Dashboard</h3>
            <span class="text-muted">Update terakhir: {{ now()->format('d M Y H:i') }}</span>
        </div>

        {{-- ================== KARTU STATISTIK (Sudah benar) ================== --}}
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

        <div class="row mb-4">

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

            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3 text-center">Status Transaksi</h5>
                        <canvas id="statusChart" height="250"></canvas>
                    </div>
                </div>
            </div>

        </div>


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
                                <td>{{ $tst->buyer->name ?? 'Guest' }}</td> {{-- Menggunakan name, bukan usr_name --}}

                                <td>Rp {{ number_format($tst->tst_total, 0, ',', '.') }}</td>

                                <td>
                                    {{-- LOGIKA BADGE DISESUAIKAN DENGAN ENUM STRING DI HOME/TRANSACTION CONTROLLER --}}
                                    @php
                                        $badgeColor = match($tst->tst_status) {
                                            'done'      => 'bg-success',
                                            'paid', 'verified' => 'bg-info', // Sudah bayar/verifikasi
                                            'sent'      => 'bg-primary', // Sedang dikirim
                                            'pending', 'waiting' => 'bg-warning',
                                            'cancelled', 'failed', 'expired' => 'bg-danger',
                                            default     => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge rounded-pill {{ $badgeColor }}">
                                        {{ ucfirst($tst->tst_status) }}
                                    </span>
                                </td>

                                <td>{{ optional($tst->tst_created_at)->format('d M Y H:i') }}</td>

                                <td>
                                    {{-- Pastikan route ini benar. Jika ini Admin Dashboard, mungkin routenya admin.orders.show --}}
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


    {{-- ================== FOOTER (Tetap Sama) ================== --}}
    <footer class="bg-white border-top py-3 mt-auto">
        <div class="container text-center">
            <small class="text-muted">&copy; 2025 Cashtify. All rights reserved.</small>
        </div>
    </footer>

    {{-- ================== CHART SCRIPT ================== --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Data dari PHP
        const productLabels = {!! json_encode(array_keys($productStats)) !!};
        const productData = {!! json_encode(array_values($productStats)) !!};
        
        // DOUGHNUT CHART DATA
        // Menggunakan array keys dan values dari $tstStatus yang sudah difilter (count > 0)
        const statusLabels = {!! json_encode(array_keys($tstStatus)) !!}.map(label => label.charAt(0).toUpperCase() + label.slice(1));
        const statusData = {!! json_encode(array_values($tstStatus)) !!};
        
        // Warna untuk status yang disinkronkan
        const statusColors = {
            'pending': '#ffc107',   // warning
            'paid': '#17a2b8',      // info
            'verified': 'primary',  // secondary/grey
            'sent': '#007bff',      // primary/blue
            'done': '#28a745',      // success
            'cancelled': '#343a40', // dark
        };

        // Memetakan warna berdasarkan label status yang ada di $tstStatus
        const backgroundColors = statusLabels.map(label => {
            return statusColors[label.toLowerCase()] || '#6c757d'; // Default grey
        });

        // BAR CHART (Produk Terjual 30 Hari Terakhir)
        new Chart(document.getElementById('productChart'), {
            type: 'bar',
            data: {
                labels: productLabels,
                datasets: [{
                    label: "Produk Terjual",
                    data: productData,
                    backgroundColor: "rgba(54, 162, 235, 0.6)"
                }]
            },
            options: {
                scales: { y: { beginAtZero: true } }
            }
        });

        // DOUGHNUT CHART (Status Transaksi)
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                // Menggunakan labels dan data yang diambil dari PHP
                labels: statusLabels, 
                datasets: [{
                    data: statusData,
                    backgroundColor: backgroundColors
                }]
            },
            options: { cutout: '70%' }
        });
    </script>

</x-layouts.admin.main>
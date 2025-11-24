<x-layouts.admin.main>
    <x-slot name="title">Detail Transaksi - {{ $transaction->tst_invoice }}</x-slot>

    <div class="container py-4">

        {{-- ======================= HEADER ======================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Detail Transaksi</h3>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        {{-- ======================= INFO UTAMA ======================= --}}
        <div class="row g-4">

            {{-- KARTU KIRI — INFORMASI TRANSAKSI --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">Informasi Transaksi</h5>

                        <div class="row mb-2">
                            <div class="col-4 text-muted">Invoice</div>
                            <div class="col-8 fw-semibold">{{ $transaction->tst_invoice }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 text-muted">Pembeli</div>
                            <div class="col-8">{{ $transaction->buyer->name ?? '-' }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 text-muted">Metode Pembayaran</div>
                            <div class="col-8">{{ $transaction->tst_payment_method }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 text-muted">Status Pembayaran</div>
                            <div class="col-8">
                                @php
                                    $pay = [
                                        '1' => ['Menunggu', 'warning'],
                                        '2' => ['Dibayar', 'success'],
                                        '3' => ['Gagal', 'danger'],
                                        '4' => ['Expired', 'secondary'],
                                    ];
                                @endphp
                                <span class="badge bg-{{ $pay[$transaction->tst_payment_status][1] }}">
                                    {{ $pay[$transaction->tst_payment_status][0] }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 text-muted">Status Transaksi</div>
                            <div class="col-8">
                                @php
                                    $st = [
                                        '1' => ['Pending', 'warning'],
                                        '2' => ['Dibayar', 'success'],
                                        '3' => ['Dikemas', 'primary'],
                                        '4' => ['Dikirim', 'info'],
                                        '5' => ['Selesai', 'success'],
                                        '6' => ['Dibatalkan', 'danger'],
                                        '7' => ['Refund', 'secondary'],
                                    ];
                                @endphp
                                <span class="badge bg-{{ $st[$transaction->tst_status][1] }}">
                                    {{ $st[$transaction->tst_status][0] }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-4 text-muted">Tanggal Dibuat</div>
                            <div class="col-8">{{ $transaction->tst_created_at }}</div>
                        </div>
                    </div>
                </div>

                {{-- ======================= ITEM BARANG ======================= --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Item Pesanan</h5>

                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($transaction->items as $item)
                                    <tr>
                                        <td>{{ $item->product->prd_name ?? 'Produk dihapus' }}</td>
                                        <td class="text-center">{{ $item->tsi_qty }}</td>
                                        <td class="text-end">Rp{{ number_format($item->tsi_price, 0, ',', '.') }}</td>
                                        <td class="text-end">
                                            Rp{{ number_format($item->tsi_price * $item->tsi_qty, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3">Subtotal</th>
                                    <th class="text-end">
                                        Rp{{ number_format($transaction->tst_subtotal, 0, ',', '.') }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="3">Ongkir</th>
                                    <th class="text-end">
                                        Rp{{ number_format($transaction->tst_shipping_cost, 0, ',', '.') }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th class="text-end">
                                        Rp{{ number_format($transaction->tst_total, 0, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ======================= KANAN — PENGIRIMAN ======================= --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">Informasi Pengiriman</h5>

                        @php $ship = $transaction->shipment; @endphp

                        @if ($ship)
                            <div class="mb-2">
                                <span class="text-muted">Kurir:</span>
                                <div class="fw-semibold">{{ $ship->shp_courier ?? '-' }}</div>
                            </div>

                            <div class="mb-2">
                                <span class="text-muted">Layanan:</span>
                                <div class="fw-semibold">{{ $ship->shp_service ?? '-' }}</div>
                            </div>

                            <div class="mb-2">
                                <span class="text-muted">Resi:</span>
                                <div class="fw-semibold">{{ $ship->shp_tracking_code ?? '-' }}</div>
                            </div>

                            <div class="mb-2">
                                <span class="text-muted">Status Pengiriman:</span>
                                <div>
                                    @php
                                        $shipStatus = [
                                            'pending' => ['Menunggu', 'secondary'],
                                            'packed' => ['Dikemas', 'primary'],
                                            'sending' => ['Dikirim', 'info'],
                                            'delivered' => ['Diterima', 'success'],
                                            'returned' => ['Dikembalikan', 'danger'],
                                        ];
                                    @endphp

                                    <span class="badge bg-{{ $shipStatus[$ship->shp_status][1] }}">
                                        {{ $shipStatus[$ship->shp_status][0] }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-2">
                                <span class="text-muted">Dikirim Pada:</span>
                                <div>{{ $ship->shp_sent_at ?? '-' }}</div>
                            </div>

                            <div class="mb-3">
                                <span class="text-muted">Diterima Pada:</span>
                                <div>{{ $ship->shp_delivered_at ?? '-' }}</div>
                            </div>

                            <div>
                                <span class="text-muted">Catatan:</span>
                                <div class="small">{{ $ship->shp_notes ?? '-' }}</div>
                            </div>
                        @else
                            <p class="text-muted mb-0">Belum ada data pengiriman.</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin.main>

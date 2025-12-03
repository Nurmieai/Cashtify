<x-layouts.admin.main>
    <x-slot name="title">Detail Transaksi - {{ $transaction->tst_invoice }}</x-slot>

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Detail Transaksi</h3>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="row g-4">

            <!-- =========================== -->
            <!--          LEFT SIDE          -->
            <!-- =========================== -->
            <div class="col-lg-8">

                {{-- ================= INFO TRANSAKSI ================= --}}
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
                                        'pending' => ['Pending', 'warning'],
                                        'paid'    => ['Dibayar', 'success'],
                                        'failed'  => ['Gagal', 'danger'],
                                        'expired' => ['Expired', 'secondary'],
                                    ];
                                @endphp

                                <span class="badge bg-{{ $pay[$transaction->tst_payment_status][1] }}">
                                    {{ $pay[$transaction->tst_payment_status][0] }}
                                </span>

                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-4 text-muted">Tanggal Dibuat</div>
                            <div class="col-8">{{ $transaction->tst_created_at }}</div>
                        </div>

                    </div>
                </div>


                {{-- ================= ITEM PEMESANAN ================= --}}
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
                                        <td>
                                            {{ $item->product->prd_name ?? 'Produk dihapus' }}

                                            <div class="small text-muted">
                                                {{ $item->tst_item_quantity }}x
                                                {{ $item->product->prd_unit ?? 'Produk' }}
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            {{ $item->tst_item_quantity }}
                                        </td>

                                        <td class="text-end">
                                            Rp{{ number_format($item->tst_item_price, 0, ',', '.') }}
                                        </td>

                                        <td class="text-end">
                                            Rp{{ number_format($item->tst_item_price * $item->tst_item_quantity, 0, ',', '.') }}
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


            <!-- =========================== -->
            <!--          RIGHT SIDE         -->
            <!-- =========================== -->
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

                            @php
                                $shipStatus = [
                                    'pending'   => ['Menunggu', 'secondary'],
                                    'packed'    => ['Dikemas', 'primary'],
                                    'sending'   => ['Dikirim', 'info'],
                                    'delivered' => ['Diterima', 'success'],
                                    'returned'  => ['Dikembalikan', 'danger'],
                                ];
                            @endphp

                            <div class="mb-2">
                                <span class="text-muted">Status Pengiriman:</span>
                                <span class="badge bg-{{ $shipStatus[$ship->shp_status][1] }}">
                                    {{ $shipStatus[$ship->shp_status][0] }}
                                </span>
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
                                <span class="text-muted">Catatan Pembeli:</span>
                                <div class="small">{{ $ship->shp_address ?? '-' }}</div>
                            </div>
                        @else
                            <p class="text-muted mb-0">Belum ada data pengiriman.</p>
                        @endif

                        {{-- Tombol real action --}}
                        <div class="mt-4">
                            <button class="btn btn-dark w-100" data-bs-toggle="modal" data-bs-target="#modalShipment">
                                <i class="bi bi-truck"></i> Update Pengiriman
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>



    {{-- ===================================================== --}}
    {{-- ===================== MODAL ========================== --}}
    {{-- ===================================================== --}}
    <div class="modal fade" id="modalShipment" tabindex="-1" aria-labelledby="modalShipmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form action="{{ route('admin.shipments.save', $transaction->tst_id) }}" method="POST" class="modal-content shadow border-0">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Update Informasi Pengiriman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    {{-- Kurir --}}
                    <div class="mb-3">
                        <label class="form-label">Kurir</label>
                        <select name="shp_courier" class="form-select" required>
                            <option value="" disabled selected>Pilih Kurir</option>
                            @php
                                $couriers = ['JNE', 'J&T', 'POS', 'SiCepat', 'Ninja', 'Wahana', 'AnterAja'];
                            @endphp
                            @foreach ($couriers as $cour)
                                <option value="{{ $cour }}" @selected(optional($ship)->shp_courier === $cour)>
                                    {{ $cour }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Layanan --}}
                    <div class="mb-3">
                        <label class="form-label">Layanan</label>
                        <select name="shp_service" class="form-select" required>
                            @php
                                $services = ['REG', 'YES', 'ECO', 'Cargo', 'Hemat'];
                            @endphp
                            <option value="" disabled selected>Pilih Layanan</option>
                            @foreach ($services as $serv)
                                <option value="{{ $serv }}" @selected(optional($ship)->shp_service === $serv)>
                                    {{ $serv }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nomor Resi --}}
                    <div class="mb-3">
                        <label class="form-label">Nomor Resi</label>
                        <input type="text" name="shp_tracking_code" class="form-control"
                               value="{{ $ship->shp_tracking_code ?? '' }}" required>
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label class="form-label">Status Pengiriman</label>
                        <select name="shp_status" class="form-select" required>
                            @php
                                $statuses = [
                                    'pending'   => 'Menunggu',
                                    'packed'    => 'Dikemas',
                                    'sending'   => 'Dikirim',
                                    'delivered' => 'Diterima',
                                    'returned'  => 'Dikembalikan',
                                ];
                            @endphp

                            @foreach ($statuses as $key => $label)
                                <option value="{{ $key }}" @selected(optional($ship)->shp_status === $key)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Catatan --}}
                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" name="shp_notes" rows="3">{{ $ship->shp_notes ?? '' }}</textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin.main>

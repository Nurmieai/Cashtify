<x-layouts.crs>
    <x-slot:title>{{ $title }}</x-slot:title>
    @section('title', 'Detail')

    <div class="container py-4" style="max-width: 720px; scroll-margin-top: 80px; margin-top: 130px;">

        <div class="mb-3">
            <a href="{{ route('orders') }}" class="btn btn-light border">
                ← Kembali
            </a>
        </div>

        <h4 class="mb-4 fw-bold">Detail Pesanan</h4>

        <div class="order-box p-4 bg-white border rounded-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="fw-bold fs-5">{{ $order->tst_invoice }}</div>
                    <div class="small text-muted">
                        {{ $order->tst_created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            <hr>

            @foreach ($order->items as $item)
                <div class="d-flex gap-3 mb-3">
                    <img src="{{ $item->product->prd_card_url
                                ? asset($item->product->prd_card_url)
                                : asset('assets/images/logo.svg') }}"
                         width="80" height="80"
                         class="rounded border"
                         style="object-fit: cover">

                    <div>
                        <div class="fw-bold">{{ $item->product->prd_name }}</div>
                        <div class="text-muted small">
                            {{ $item->tst_item_quantity }}x {{ $item->product->prd_unit ?? 'Produk' }}
                        </div>
                    </div>
                </div>
            @endforeach

            <hr>

            {{-- TOTAL --}}
            <div class="row mb-2">
                <div class="col-6 text-muted small">Total Pembelian</div>
                <div class="col-6 text-end text-primary fw-bold">
                    Rp {{ number_format($order->tst_total, 0, ',', '.') }}
                </div>
            </div>

            {{-- METODE PEMBAYARAN --}}
            <div class="row mb-2">
                <div class="col-6 text-muted small">Metode Pembayaran</div>
                <div class="col-6 text-end">
                    {{ ucfirst(str_replace('_', ' ', $order->tst_payment_method)) }}
                </div>
            </div>

            {{-- STATUS PEMBAYARAN STRING --}}
            <div class="row mb-3">
                <div class="col-6 text-muted small">Status Pembayaran</div>
                <div class="col-6 text-end">

                    @php
                        $payStatus = [
                            'pending'   => ['Belum Dibayar', 'bg-warning text-dark'],
                            'paid'      => ['Pembayaran Berhasil', 'bg-success'],
                            'failed'    => ['Pembayaran Gagal', 'bg-danger'],
                            'cancelled' => ['Dibatalkan', 'bg-danger'],
                        ];

                        $ps  = $order->tst_payment_status;
                        $lbl = $payStatus[$ps][0] ?? 'Tidak Diketahui';
                        $clr = $payStatus[$ps][1] ?? 'bg-dark';
                    @endphp

                    <span class="badge {{ $clr }}">{{ $lbl }}</span>

                </div>
            </div>

            <hr>

            {{-- JUMLAH PRODUK --}}
            <div class="row mb-3">
                <div class="col-6 text-muted small">Jumlah Produk</div>
                <div class="col-6 text-end">
                    {{ $order->items->sum('tst_item_quantity') }} produk
                </div>
            </div>

        </div>

    </div>

</x-layouts.crs>

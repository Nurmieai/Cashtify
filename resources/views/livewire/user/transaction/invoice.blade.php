<x-layouts.tst>
    <x-slot:title>Invoice #{{ $transaction->tst_invoice }}</x-slot:title>
@php
    $isPending = in_array($transaction->tst_payment_status, ['pending', 1]);
    $isPaid    = in_array($transaction->tst_payment_status, ['paid', 2]);
@endphp

    <div class="container py-4">

        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Invoice #{{ $transaction->tst_invoice }}</h4>

            <a href="{{ route('landing') }}" class="btn btn-outline-secondary">
                ← Kembali ke Halaman Utama
            </a>
        </div>

        <!-- CARD: STATUS & PAYMENT -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">

                <div class="mb-3">
                    <strong>Status Pembayaran:</strong><br>
                        @if($isPending)
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($isPaid)
                            <span class="badge bg-success">Lunas</span>
                        @else
                            <span class="badge bg-danger">Gagal</span>
                        @endif
                </div>

                <p><strong>Metode Pembayaran:</strong> {{ ucfirst($transaction->tst_payment_method) }}</p>

                <p><strong>Total Pembayaran:</strong>
                    Rp {{ number_format($transaction->tst_total, 0, ',', '.') }}
                </p>

            </div>
        </div>

        <!-- CARD: DETAIL PRODUK -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">Detail Produk</h6>
            </div>

            <ul class="list-group list-group-flush">
                @foreach ($transaction->items as $item)
                    <li class="list-group-item d-flex justify-content-between">

                        <div>
                            <strong>{{ $item->product->prd_name }}</strong><br>
                            <small>
                                {{ $item->tsi_quantity }} ×
                                Rp {{ number_format($item->tsi_price, 0, ',', '.') }}
                            </small>
                        </div>

                        <div>
                            <strong>
                                Rp {{ number_format($item->tsi_subtotal, 0, ',', '.') }}
                            </strong>
                        </div>

                    </li>
                @endforeach
            </ul>
        </div>

        <!-- CARD: ALAMAT -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">Alamat Pengiriman</h6>
            </div>

            <div class="card-body">
                <p class="mb-1">{{ $transaction->shipment->shp_address }}</p>

                {{-- opsional, kalau masih pakai koordinat --}}
                @if($transaction->shipment->shp_latitude)
                    <small class="text-muted">
                        Lat: {{ $transaction->shipment->shp_latitude }},
                        Long: {{ $transaction->shipment->shp_longitude }}
                    </small>
                @endif
            </div>
        </div>

        @if($isPending)
        <div class="text-end">
            <a href="{{ route('checkout.product.pay', $transaction->tst_id) }}"
            class="btn btn-outline-secondary">
                Bayar Sekarang
            </a>
        </div>
        @endif


    </div>
</x-layouts.tst>

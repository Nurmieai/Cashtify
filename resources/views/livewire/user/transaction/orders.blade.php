<x-layouts.crs>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="container py-4" style="max-width: 720px; margin-top: 130px;">

        <h4 class="mb-4 fw-bold">Pesanan Saya</h4>

        <div class="mb-3">
            <a href="{{ route('landing') }}" class="btn btn-light border">
                ← Kembali
            </a>
        </div>

        @if ($orders->count() == 0)
            <div class="alert alert-warning text-center">Belum ada pesanan.</div>
        @endif

        <div class="mt-4">
            @foreach ($orders as $order)
                @php
                    $item = $order->items->first();
                    $product = $item?->product;
                @endphp

                <div class="mb-4 border rounded-3 bg-white shadow-sm overflow-hidden">

                    <div class="p-2 text-center">
                        <x-status-badge :status="$order->tst_status" />
                    </div>

                    <div class="p-4">

                        <div class="text-muted small mb-3">
                            {{ $order->tst_created_at?->format('d F Y, H:i') }}
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-2">

                            <img src="{{ $product?->prd_card_url ? asset($product->prd_card_url) : asset('assets/images/logo.svg') }}"
                                 width="80" height="80"
                                 class="rounded border"
                                 style="object-fit: cover">

                            <div class="flex-grow-1">

                                <div class="fw-bold mb-1 fs-6">
                                    {{ $product->prd_name ?? 'Produk tidak ditemukan' }}
                                </div>

                                <div class="text-muted small">
                                    {{ $product->prd_category ?? '' }}
                                </div>

                                <div class="text-muted small mt-1">
                                    {{ $item?->tst_item_quantity ?? 0 }}x {{ $product->prd_unit ?? 'Produk' }}
                                </div>

                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">Total Pembelian</div>
                                <div class="fw-bold text-primary fs-6">
                                    Rp {{ number_format($order->tst_total, 0, ',', '.') }}
                                </div>
                            </div>

                            <a href="{{ route('orders.detail', $order->tst_id) }}"
                               class="btn btn-outline-primary btn-sm px-3">
                                Detail
                            </a>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>

    </div>
</x-layouts.crs>

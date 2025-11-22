<x-layouts.tst>
    <x-slot:title>Invoice #{{ $transaction->tst_invoice }}</x-slot:title>

    <div class="container py-4">

        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Invoice {{ $transaction->tst_invoice }}</h5>
            </div>
            <div class="card-body">
                <p><strong>Status Pembayaran:</strong>
                    @if($transaction->tst_payment_status == 1)
                        <span class="badge bg-warning">Pending</span>
                    @elseif($transaction->tst_payment_status == 2)
                        <span class="badge bg-success">Lunas</span>
                    @else
                        <span class="badge bg-secondary">Unknown</span>
                    @endif
                </p>

                <p><strong>Metode:</strong> {{ $transaction->tst_payment_method }}</p>
                <p><strong>Total:</strong> Rp {{ number_format($transaction->tst_total, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Produk</h6>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($transaction->items as $item)
                    <li class="list-group-item d-flex justify-content-between">
                        <div>
                            <strong>{{ $item->tst_item_product_name }}</strong><br>
                            <small>{{ $item->tst_item_quantity }} x
                                Rp {{ number_format($item->tst_item_price, 0, ',', '.') }}
                            </small>
                        </div>
                        <div>
                            Rp {{ number_format($item->tst_item_subtotal, 0, ',', '.') }}
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Alamat Pengiriman</h6>
            </div>
            <div class="card-body">
                <p>{{ $transaction->shipment->shp_address }}</p>
                <p>Lat: {{ $transaction->shipment->shp_latitude }}</p>
                <p>Long: {{ $transaction->shipment->shp_longitude }}</p>
            </div>
        </div>

        @if($transaction->tst_payment_status == 1)
        <div class="text-end">
            <a href="{{ route('checkout.product.pay', $transaction->tst_id) }}"
            class="btn btn-primary">
                Bayar Sekarang
            </a>
        @endif
        </div>

    </div>
</x-layouts.tst>

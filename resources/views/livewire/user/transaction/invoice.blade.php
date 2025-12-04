<x-layouts.tst>
    <x-slot:title>Invoice #{{ $transaction->tst_invoice }}</x-slot:title>

    @php
        $walletRaw = Auth::user()->usr_sys_note;

        $wallet = is_string($walletRaw)
            ? json_decode($walletRaw, true)
            : ($walletRaw ?? []);

        $wallet['saldo'] = $wallet['saldo'] ?? [
            'bca' => 0,
            'dana' => 0,
        ];

        $isPending = in_array($transaction->tst_payment_status, ['pending', 1]);
        $isPaid    = in_array($transaction->tst_payment_status, ['paid', 2]);

        $paymentMethod = $transaction->tst_payment_method;
        $availableBalance = $wallet['saldo'][$paymentMethod] ?? 0;
    @endphp

    <div class="container py-4">

        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif


        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Invoice #{{ $transaction->tst_invoice }}</h4>
            <a href="{{ route('landing') }}" class="btn btn-outline-secondary">← Kembali</a>
        </div>

        <!-- STATUS -->
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

                <p><strong>Metode Pembayaran:</strong> {{ ucfirst($paymentMethod) }}</p>
                <p><strong>Total Pembayaran:</strong>
                    Rp {{ number_format($transaction->tst_total, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- ITEMS -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light"><h6 class="mb-0">Detail Produk</h6></div>
            <ul class="list-group list-group-flush">
                @foreach ($transaction->items as $item)
                    <li class="list-group-item d-flex justify-content-between">
                        <div>
                            <strong>{{ $item->product->prd_name }}</strong><br>
                            <small>
                                {{ $item->tst_item_quantity }} ×
                                Rp {{ number_format($item->tst_item_price, 0, ',', '.') }}
                            </small>
                        </div>
                        <strong>
                            Rp {{ number_format($item->tst_item_subtotal, 0, ',', '.') }}
                        </strong>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- ALAMAT PENGIRIMAN -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light"><h6 class="mb-0">Alamat Pengiriman</h6></div>
            <div class="card-body">
                <p class="mb-1">{{ $transaction->shipment->shp_address }}</p>

                @if($transaction->shipment->shp_latitude)
                    <small class="text-muted">
                        <div class="mb-3 p-3 bg-light rounded-3 shadow-sm">
                            <strong>Lokasi Terpilih:</strong><br>
                            <span id="loc-lat">Latitude: -</span><br>
                            <span id="loc-lng">Longitude: -</span><br>
                            <span id="loc-city" class="text-primary fw-semibold"></span>
                        </div>
                    </small>
                @endif
            </div>
        </div>

        @if($isPending)
        <div class="text-end">
            <button id="btn-pay" class="btn btn-outline-secondary">Bayar Sekarang</button>
        </div>
        @endif


        {{-- Modal Pembayaran --}}
        <div class="modal fade" id="paymentModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p><strong>Metode Pembayaran:</strong> {{ ucfirst($paymentMethod) }}</p>
                        <p><strong>Total:</strong> Rp {{ number_format($transaction->tst_total, 0, ',', '.') }}</p>

                        <p><strong>Saldo Anda:</strong>
                            Rp <span id="user-balance">{{ number_format($availableBalance, 0, ',', '.') }}</span>
                        </p>

                        <div id="saldo-warning" class="alert alert-danger d-none">
                            Saldo tidak mencukupi!
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button id="confirm-pay" class="btn btn-primary">Bayar Sekarang</button>
                    </div>

                </div>
            </div>
        </div>

    </div>


    <script>
    let userBalance = {{ $availableBalance }};
    let totalAmount = {{ $transaction->tst_total }};
    let payUrl = "{{ route('checkout.product.pay', $transaction->tst_id) }}";

    // muncul modal
    document.getElementById('btn-pay')?.addEventListener('click', () => {
        new bootstrap.Modal(document.getElementById('paymentModal')).show();
    });

    // tombol bayar
    document.getElementById('confirm-pay')?.addEventListener('click', () => {
        if (userBalance >= totalAmount) {
            window.location.href = payUrl;
        } else {
            document.getElementById('saldo-warning').classList.remove('d-none');
        }
    });

    // map lat/lng
    document.addEventListener("DOMContentLoaded", function () {
        const lat = {{ $transaction->shipment->shp_latitude ?? 'null' }};
        const lng = {{ $transaction->shipment->shp_longitude ?? 'null' }};

        if (!lat || !lng) return;

        document.getElementById("loc-lat").innerText = "Latitude: " + lat;
        document.getElementById("loc-lng").innerText = "Longitude: " + lng;

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                if (data.address) {
                    const city = data.address.city ||
                                data.address.town ||
                                data.address.village ||
                                data.address.suburb ||
                                data.address.county || "-";

                    document.getElementById("loc-city").innerText = "Kota: " + city;
                }
            });
    });
    </script>

</x-layouts.tst>

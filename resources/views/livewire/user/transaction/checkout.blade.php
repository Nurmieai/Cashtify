<x-layouts.app>
    <x-slot name="title">Checkout</x-slot>
    @section('title', 'Checkout')

    <section class="py-5" style="margin-top: 120px;">
        <div class="container" style="max-width: 800px;">

            <!-- HEADER -->
            <div class="mb-4 text-center">
                <h3 class="fw-bold">Checkout</h3>
                <p class="text-muted">Periksa kembali pesanan Anda sebelum melanjutkan pembayaran.</p>
            </div>

            <!-- CARD WRAPPER -->
            <div class="card shadow-sm border-0 rounded-4 p-4">

                <!-- PRODUK -->
                <h5 class="fw-semibold mb-3">Detail Produk</h5>
                <div class="d-flex align-items-center mb-4">
                    <img src="{{ $product->prd_card_url ? asset($product->prd_card_url) : asset('assets/images/logo.svg') }}"
                         class="rounded-3 me-3"
                         style="width: 100px; height: 100px; object-fit: contain;">

                    <div>
                        <h6 class="fw-bold mb-1">{{ $product->prd_name }}</h6>
                        <p class="text-primary fw-semibold mb-0">
                            Rp {{ number_format($product->prd_price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- JUMLAH -->
                <div class="mb-4">
                    <label class="fw-semibold mb-2">Jumlah</label>
                    <input type="number"
                           min="1"
                           value="1"
                           class="form-control rounded-3 shadow-sm"
                           style="max-width: 150px;">
                </div>

                <!-- PEMBAYARAN -->
                <h5 class="fw-semibold mb-3">Metode Pembayaran</h5>

                <div class="row g-3 mb-4">

                    <!-- QRIS -->
                    <div class="col-md-4">
                        <div class="card payment-option border-0 shadow-sm p-3 rounded-3 text-center"
                             style="cursor: pointer;">
                            <img src="{{ asset('assets/images/payment/qris.png') }}" style="height: 40px;">
                            <p class="mt-2 mb-0 fw-semibold">QRIS</p>
                        </div>
                    </div>

                    <!-- Transfer Bank -->
                    <div class="col-md-4">
                        <div class="card payment-option border-0 shadow-sm p-3 rounded-3 text-center"
                             style="cursor: pointer;">
                            <img src="{{ asset('assets/images/payment/bank.png') }}" style="height: 40px;">
                            <p class="mt-2 mb-0 fw-semibold">Transfer Bank</p>
                        </div>
                    </div>

                    <!-- E-Wallet -->
                    <div class="col-md-4">
                        <div class="card payment-option border-0 shadow-sm p-3 rounded-3 text-center"
                             style="cursor: pointer;">
                            <img src="{{ asset('assets/images/payment/ewallet.png') }}" style="height: 40px;">
                            <p class="mt-2 mb-0 fw-semibold">E-Wallet</p>
                        </div>
                    </div>
                </div>

                <!-- TOMBOL INVOICE -->
                <a href="{{ route('checkout.invoice', $product->prd_id) }}"
                   class="btn btn-danger w-100 py-3 fw-semibold rounded-3 shadow-sm">
                    Buat Invoice
                </a>

            </div>
        </div>
    </section>

</x-layouts.app>

<x-layouts.app>
    <x-slot name="title">Landing Page</x-slot>
    @section('title', 'Landing Page')

    <!-- SECTION: PRODUK -->
    <section class="py-4" id="Products" style="scroll-margin-top: 80px; margin-top: 130px;">
        <div class="container">
            <h4 class="fw-bold mb-4 text-center">> Produk Terpopuler <</h4>

            {{-- Slider Produk Dummy --}}
            <div class="product-slider">
                <div class="product-track d-flex">
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="cards mx-3 border-0 shadow-sm product-card">
                            <img src="{{ asset('assets/images/logo.svg') }}" class="card-img-top" alt="Produk {{ $i }}">
                            <div class="card-body text-center">
                                <h6 class="fw-semibold mb-1">Produk {{ $i }}</h6>
                                <p class="text-primary fw-bold mb-0">Rp {{ number_format(10000 * $i, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>

    <!-- GRID PRODUK -->
    <section class="py-5" id="Products" style="scroll-margin-top: 80px; margin-top: 50px;">
        <div class="container">
            <h4 class="fw-bold mb-4 text-center">> Semua Produk <</h4>

            @if($products->count() > 0)
                <div class="row justify-content-center g-4">
                    @foreach ($products as $product)
                        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                            <div class="card product-card w-100 border shadow-sm"
                                 data-bs-toggle="modal"
                                 data-bs-target="#productModal{{ $product->prd_id }}"
                                 style="cursor: pointer; transition: .25s ease">

                                <div class="card-image-wrapper p-2">
                                    <img src="{{ asset('assets/images/logo.svg') }}"
                                         class="card-img-top rounded-3"
                                         style="object-fit: contain; height: 200px;">
                                </div>

                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="fw-bold mb-1">{{ $product->prd_name }}</h6>
                                            <p class="text-primary fw-semibold mb-0">
                                                Rp {{ number_format($product->prd_price, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        @if($product->prd_status === 'tersedia')
                                            <span class="badge bg-success">Tersedia</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Tersedia</span>
                                        @endif
                                    </div>

                                    <p class="mt-2 small text-muted">
                                        {{ Str::limit($product->prd_description, 60) }}
                                    </p>
                                </div>

                            </div>
                        </div>

                        {{-- Modal Produk --}}
                        <div class="modal fade"
                             id="productModal{{ $product->prd_id }}"
                             tabindex="-1"
                             aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-body p-4">
                                        <div class="row">
                                            <div class="col-md-6 text-center">
                                                <img src="{{ asset('assets/images/logo.svg') }}"
                                                     class="img-fluid rounded-3 mb-3"
                                                     style="max-height: 280px; object-fit: cover;">
                                            </div>

                                            <div class="col-md-6">
                                                <h5 class="fw-bold">{{ $product->prd_name }}</h5>
                                                <p class="text-muted">{{ $product->prd_description }}</p>
                                                <h6 class="text-primary fw-bold">
                                                    Rp {{ number_format($product->prd_price, 0, ',', '.') }}
                                                </h6>

                                                @if($product->prd_status === 'tersedia')
                                                    <span class="badge bg-success mb-3">Tersedia</span>
                                                @else
                                                    <span class="badge bg-danger mb-3">Tidak Tersedia</span>
                                                @endif

                                                <div class="d-flex gap-2">
                                                    <button class="btn btn-outline-primary w-50">
                                                        🛒 Keranjang
                                                    </button>
                                                    <button class="btn btn-primary w-50">
                                                        ⚡ Beli Sekarang
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            @else
                <p class="text-center text-muted">Belum ada produk.</p>
            @endif

        </div>
    </section>

    <!-- SECTION: LAYANAN KAMI -->
    <section id="Customer-Service" class="py-5 bg-light" style="margin-top: 120px; scroll-margin-top: 180px;">
        <div class="container">
            <h4 class="fw-bold text-center mb-4">Layanan Kami</h4>

            <div class="row g-4 justify-content-center">

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 p-4 text-center">
                        <i class="bi bi-chat-dots fs-1 mb-3 text-primary"></i>
                        <h6 class="fw-bold">Customer Support</h6>
                        <p class="text-muted small">Kami siap membantu 24/7 terkait produk & transaksi.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 p-4 text-center">
                        <i class="lni lni-delivery fs-1 mb-3 text-primary"></i>
                        <h6 class="fw-bold">Pengiriman Cepat</h6>
                        <p class="text-muted small">Pengiriman cepat & aman langsung ke lokasi Anda.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 p-4 text-center">
                        <i class="lni lni-protection fs-1 mb-3 text-primary"></i>
                        <h6 class="fw-bold">Pembayaran Aman</h6>
                        <p class="text-muted small">Transaksi aman dengan sistem terverifikasi.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION: TENTANG KAMI -->
    <section id="footer" class="py-5" style="margin-top: 120px; margin-bottom: 200px; scroll-margin-top: 200px;">
        <div class="container text-center">
            <h4 class="fw-bold mb-3">ℹ️ Tentang Kami</h4>
            <p class="text-muted mx-auto" style="max-width: 600px;">
                Cashtify adalah platform jual-beli modern dengan layanan cepat, aman, dan terpercaya.
                Kami hadir untuk membantu kamu bertransaksi dengan nyaman setiap hari 💛
            </p>
        </div>
    </section>

    <!-- SCROLL TOP -->
    <a href="#" class="scroll-top btn-hover">
        <i class="lni lni-chevron-up"></i>
    </a>

</x-layouts.app>

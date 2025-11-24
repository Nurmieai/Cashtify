<x-layouts.app>
    <x-slot name="title">Landing Page</x-slot>

    <!-- SECTION: PRODUK TERPOPULER -->
    <section class="py-4" id="ProductsPopular" style="scroll-margin-top: 80px; margin-top: 130px;">
        <div class="container">
            <h4 class="fw-bold mb-4 text-center text-danger">
                &gt; Produk Terpopuler &lt;
            </h4>

            <div class="product-slider">
                <div class="product-track d-flex">
                    @forelse ($products as $product)
                        <div class="cards mx-3 product-card">
                            <img src="{{ $product->prd_card_url ? asset($product->prd_card_url) : asset('assets/images/logo.svg') }}"
                                 alt="{{ $product->prd_name }}">
                            <div class="card-body text-center">
                                <h6 class="product-title">
                                    {{ $product->prd_name }}
                                </h6>
                                <p class="product-price mb-0">
                                    Rp {{ number_format($product->prd_price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Tidak ada produk tersedia.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- GRID PRODUK SEMUA -->
    <section class="py-5" id="ProductsAll" style="scroll-margin-top: 80px; margin-top: 50px;">
        <div class="container">
            <h4 class="fw-bold mb-4 text-center text-danger">
                &gt; Semua Produk &lt;
            </h4>

            @if($products->count() > 0)
                <div class="row justify-content-center g-4">
                    @foreach ($products as $product)
                        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">

                            <div class="card product-card w-100 border-0 shadow-sm rounded-4"
                                 data-bs-toggle="modal"
                                 data-bs-target="#productModal{{ $product->prd_id }}"
                                 style="cursor: pointer; transition: .25s;">

                                <div class="card-image-wrapper p-3">
                                    <img src="{{ $product->prd_card_url ? asset($product->prd_card_url) : asset('assets/images/logo.svg') }}"
                                         class="card-img-top rounded-3"
                                         style="object-fit: contain; height: 200px;"
                                         alt="{{ $product->prd_name }}">
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="fw-bold">{{ $product->prd_name }}</h6>
                                            <p class="text-danger fw-bold mb-0">
                                                Rp {{ number_format($product->prd_price, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        @if($product->prd_status === 'tersedia')
                                            <span class="badge bg-success rounded-pill px-3 py-2">Tersedia</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill px-3 py-2">Tidak Tersedia</span>
                                        @endif
                                    </div>

                                    <p class="mt-2 small text-muted">
                                        {{ \Illuminate\Support\Str::limit($product->prd_description, 60) }}
                                    </p>
                                </div>

                            </div>
                        </div>

                        <!-- MODAL PRODUK -->
                        <div class="modal fade" id="productModal{{ $product->prd_id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                                    <!-- HEADER MODAL -->
                                    <div class="modal-header py-3 text-white" style="background-color: #c82333;">
                                        <h5 class="modal-title fw-semibold">Detail Produk — {{ $product->prd_name }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body p-4">
                                        <div class="row g-4">
                                            <div class="col-md-6 text-center">
                                                <img src="{{ $product->prd_card_url ? asset($product->prd_card_url) : asset('assets/images/logo.svg') }}"
                                                     class="rounded-3"
                                                     style="object-fit: contain; height: 200px;"
                                                     alt="{{ $product->prd_name }}">
                                            </div>

                                            <div class="col-md-6">
                                                <h4 class="fw-bold mb-2">{{ $product->prd_name }}</h4>
                                                <p class="text-muted mb-3">
                                                    {{ $product->prd_description }}
                                                </p>

                                                <h5 class="text-danger fw-bold mb-2">
                                                    Rp {{ number_format($product->prd_price, 0, ',', '.') }}
                                                </h5>

                                                @if($product->prd_status === 'tersedia')
                                                    <span class="badge bg-success rounded-pill px-3 py-2 mb-3">Tersedia</span>
                                                @else
                                                    <span class="badge bg-danger rounded-pill px-3 py-2 mb-3">Tidak Tersedia</span>
                                                @endif

                                                @if (Auth::check() && Auth::user()->hasRole('Pembeli'))
                                                    <div class="d-flex gap-3 mt-3">
                                                        <form action="{{ route('cart.add', $product->prd_id) }}" method="POST" class="w-50">
                                                            @csrf
                                                            <input type="hidden" name="quantity" value="1">
                                                            <button type="submit"
                                                                class="btn btn-outline-danger w-100 py-2 rounded-3 fw-semibold">
                                                                🛒 Tambah
                                                            </button>
                                                        </form>

                                                        <a href="{{ route('checkout.product', $product->prd_id) }}"
                                                           class="btn btn-danger w-50 py-2 rounded-3 fw-semibold">
                                                            ⚡ Beli
                                                        </a>
                                                    </div>
                                                @endif
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

    <section id="Customer-Service" class="py-5 bg-light" style="margin-top: 130px;margin-bottom: 100px;scroll-margin-top: 290px; scroll-margin-bottom: 250px;">
        <div class="container">
            <h4 class="fw-bold text-center mb-4 text-danger">Layanan Kami</h4>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="card service-card shadow-sm border-0 p-4 text-center rounded-4">
                        <div class="icon-wrapper mx-auto fs-1 mb-3">
                            <i class="bi bi-chat-dots"></i>
                        </div>
                        <h6 class="fw-bold text-danger">Customer Support</h6>
                        <p class="text-muted small">
                            Kami siap membantu 24/7 terkait produk & transaksi.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card shadow-sm border-0 p-4 text-center rounded-4">
                        <div class="icon-wrapper mx-auto fs-1 mb-3">
                            <i class="lni lni-delivery"></i>
                        </div>
                        <h6 class="fw-bold text-danger">Pengiriman Cepat</h6>
                        <p class="text-muted small">
                            Pengiriman cepat & aman langsung ke lokasi Anda.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card shadow-sm border-0 p-4 text-center rounded-4">
                        <div class="icon-wrapper mx-auto fs-1 mb-3">
                            <i class="lni lni-protection"></i>
                        </div>
                        <h6 class="fw-bold text-danger">Pembayaran Aman</h6>
                        <p class="text-muted small">
                            Transaksi aman dengan sistem terverifikasi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: TENTANG KAMI -->
    <section id="footer" class="py-5" style="margin-top: 150px; margin-bottom: 200px; scroll-margin-top: 220px;">
        <div class="container text-center">
            <h4 class="fw-bold mb-3 text-danger">ℹ️ Tentang Kami</h4>
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

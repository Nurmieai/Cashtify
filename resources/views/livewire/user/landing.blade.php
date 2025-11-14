    <x-layouts.app>
        <x-slot name="title">Landing Page</x-slot>

        @section('title', 'Landing Page')
        <section class="py-4" style="scroll-margin-top: 80px; margin-top: 130px;" id="hero-area">
            <div class="container">
                <h4 class="fw-bold mb-4 text-center">🔥 Produk Terpopuler</h4>
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

        <!-- CATEGORY BUTTONS -->
        <section class="py-5" style="scroll-margin-top: 80px; margin-top: 130px;" id="hero-area">
        <div class="container">
            <h4 class="fw-bold mb-4 text-center">🔥 Produk Terpopuler</h4>

            @if($products->count() > 0)
            <div class="row justify-content-center g-4">
                @foreach ($products as $product)
                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                    <div class="card product-card w-100 border shadow-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#productModal{{ $product->prd_id }}"
                        style="cursor: pointer; transition: all 0.25s ease-in-out; border-color: #e5e7eb;">

                        <div class="card-image-wrapper p-2">
  <img src="{{ asset('assets/images/logo.svg') }}"
       alt="{{ $product->prd_name }}"
       class="card-img-top rounded-3"
       style="object-fit: contain; height: 200px; width: 100%;">
</div>


                    <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="text-start">
                                    <h6 class="fw-bold fs-6 mb-1">{{ $product->prd_name }}</h6>
                                    <p class="text-primary fw-semibold fs-6 mb-0">
                                        Rp {{ number_format($product->prd_price, 0, ',', '.') }}
                                    </p>
                                </div>
                                @if($product->prd_status === 'tersedia')
                                    <span class="badge bg-success align-self-start">Tersedia</span>
                                @else
                                    <span class="badge bg-danger align-self-start">Tidak Tersedia</span>
                                @endif
                            </div>
                            <p class="mt-2 small text-muted">
                                {{ Str::limit($product->prd_description, 60) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Modal detail produk --}}
                <div class="modal fade" id="productModal{{ $product->prd_id }}" tabindex="-1" aria-labelledby="productModalLabel{{ $product->prd_id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <div class="modal-body p-4">
                                <div class="row">
                                    <div class="col-md-6 text-center">
                                        <img src="{{ asset('assets/images/logo.svg') }}"
                                            alt="{{ $product->prd_name }}"
                                            class="img-fluid rounded-3 mb-3"
                                            style="max-height: 280px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="fw-bold mb-2">{{ $product->prd_name }}</h5>
                                        <p class="text-muted">{{ $product->prd_description }}</p>
                                        <h6 class="text-primary fw-bold">
                                            Rp {{ number_format($product->prd_price, 0, ',', '.') }}
                                        </h6>

                                        @if($product->prd_status === 'tersedia')
                                            <span class="badge bg-success mb-3">Tersedia</span>
                                        @else
                                            <span class="badge bg-danger mb-3">Tidak Tersedia</span>
                                        @endif

                                        <div class="d-flex justify-content-between">
                                            <button class="btn btn-outline-primary w-50 me-2">
                                                🛒 Masukkan ke Keranjang
                                            </button>
                                            <button class="btn btn-primary w-50">
                                                ⚡ Beli Langsung
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
    <a href="#" class="scroll-top btn-hover">
        <i class="lni lni-chevron-up"></i>
    </a>

    </x-layouts.app>

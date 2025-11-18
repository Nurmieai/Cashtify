<x-layouts.admin.main>
    <div class="container-fluid py-4">
        {{-- Search + Filter --}}
        <div class="card border-secondary shadow mb-4 p-3">
            <div class="d-flex flex-wrap gap-2">
                <form action="" method="GET" class="d-flex flex-grow">
                    <input type="text" name="search" class="form-control me-2"
                        placeholder="Masukan Nama Produk"
                        value="{{ request('search') }}">
                    <button class="btn btn-outline-success"><i class="bi bi-search"></i></button>
                </form>
                <div class="ms-auto d-flex gap-2">
                    <a href="{{ route('products.create') }}" class="btn btn-outline-primary"><i class="bi bi-plus-lg"></i></a>
                </div>
            </div>
        </div>

    {{-- Product Grid --}}
    @if($products->count() > 0)
        <div class="row justify-content-center g-4">
            @foreach ($products as $product)
                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                    <div class="card bg-dark text-white border-secondary product-card w-100 shadow-sm"
                         style="transition: .25s ease; cursor: pointer;">

                        {{-- Image --}}
                        <div class="card-image-wrapper p-3">
                            <img
                                src="{{ $product->prd_card_url ? asset($product->prd_card_url) : asset('assets/images/logo.svg') }}"
                                alt="Product Image"
                                class="card-img-top rounded-3"
                                style="object-fit: contain; height: 200px;">
                        </div>


                        {{-- Body --}}
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex justify-content-between align-items-start mb-2">
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

                            {{-- Bottom Buttons --}}
                            <div class="mt-auto text-center">
                                <div class="btn-group dropup">
                                    <button
                                        type="button"
                                        class="btn btn-warning text-dark fw-semibold dropdown-toggle"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                    >
                                        <i class="bi bi-stack"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-dark">
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('products.show', $product->prd_id) }}">
                                                Detail
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('products.edit', $product->prd_id) }}">
                                                Ubah
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>

    @else
        <p class="text-center text-white mt-5">Tidak ada produk ditemukan.</p>
    @endif

</div>

</x-layouts.admin.main>

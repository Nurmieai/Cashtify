<x-layouts.admin.main>

<style>
    .card-body,
    .table-responsive {
        overflow: visible !important;
        white-space: normal !important;
    }
    table {
        width: 100% !important;
    }
    img, .card-img-top {
        max-width: 100% !important;
    }
</style>

<x-table_data :paginator="$products" title="Daftar Produk">

    <x-slot:header>
        <div class="d-flex justify-content-between align-items-center w-100 mb-3 mt-4">
            <form action="" method="GET" class="d-flex flex-grow">
                    <input type="text" name="search" class="form-control me-2"
                        placeholder="Masukan Nama Produk"
                        value="{{ request('search') }}">
                    <button class="btn btn-outline-success btn-lg"><i class="bi bi-search"></i></button>
                </form>
            <a href="{{ route('products.create') }}"
               class="btn btn-lg btn-outline-primary ms-2" title="Tambah Produk">
                <i class="bi bi-plus-lg"></i>
            </a>
            <a href="{{ route('products.trashed') }}" class="btn ms-2 btn-lg btn-outline-warning">
                <i class="bi bi-trash3-fill"></i>
            </a>
        </div>
        {{-- Notifikasi --}}
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h5>{{ session('success') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </x-slot:header>

    <tr>
        <td colspan="1">
            <div class="row justify-content-center g-4">
                @forelse ($products as $product)
                    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                        <div class="card bg-dark text-white border-secondary product-card w-100 shadow-sm"
                            style="transition: .25s ease; cursor: pointer;">

                            <div class="card-image-wrapper p-3">
                                <img src="{{ $product->prd_card_url ? asset($product->prd_card_url) : asset('assets/images/logo.svg') }}"
                                     class="card-img-top rounded-3"
                                     style="object-fit: contain; height: 200px;">
                            </div>

                            <div class="card-body d-flex flex-column justify-content-between">

                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $product->prd_name }}</h6>
                                        <p class="text-primary fw-semibold mb-0">
                                            Rp {{ number_format($product->prd_price, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    @if ($product->prd_status === 'tersedia')
                                        <span class="badge bg-success">Tersedia</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Tersedia</span>
                                    @endif
                                </div>

                                <div class="mt-auto text-center">
                                    <div class="btn-group dropup w-100">
                                        <button class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="bi bi-stack"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('products.show', $product->prd_id) }}">
                                                    Detail
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('products.edit', $product->prd_id) }}">
                                                    Ubah
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted mt-4">Tidak ada produk ditemukan…</p>
                @endforelse
            </div>
        </td>
    </tr>

</x-table_data>

</x-layouts.admin.main>

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
        <x-slot:header></x-slot:header>
        <tr>
            <td colspan="1">
                <div class="row justify-content-center g-4">
                    @foreach ($products as $product)
                        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                            <div class="card bg-dark text-white border-secondary product-card w-100 shadow-sm"
                                style="transition: .25s ease; cursor: pointer;">
                                <div class="card-image-wrapper p-3">
                                    <img
                                        src="{{ $product->prd_card_url ? asset($product->prd_card_url) : asset('assets/images/logo.svg') }}"
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
                                        @if($product->prd_status === 'tersedia')
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
            </td>
        </tr>
    </x-table_data>
</x-layouts.admin.main>

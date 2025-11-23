<x-layouts.admin.main>
    <x-slot:title>{{ $title }}</x-slot:title>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h5>{{ session('success') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-0 p-3 bg-body shadow rounded">
        <div class="col-12 col-md-4 d-flex justify-content-center">
            <img
                src="{{ asset($product->prd_card_url ?? 'assets/placeholder.png') }}"
                class="img-fluid shadow mb-2"
                style="height: 300px; width: auto; object-fit: contain;"
                alt="Gambar Produk">
        </div>

        <div class="col-12 col-md-8 ps-md-3 mt-3 mt-md-0">
            <h4 class="fw-bold mb-1 mt-4">
                {{ $product->prd_name }}
            </h4>

            {{-- HARGA --}}
            <p class="mb-1 text-primary fw-semibold fs-5">
                Rp {{ number_format($product->prd_price, 0, ',', '.') }}
            </p>

            @if (!empty($product->prd_category))
                <p class="text-muted mb-1">
                    Kategori: <strong>{{ $product->prd_category }}</strong>
                </p>
            @endif

            <hr class="mb-2">

            <p class="text-muted"
                style="line-height: 1.5; white-space: pre-line; word-wrap: break-word; overflow-wrap: break-word;">
                {{ $product->prd_description }}
            </p>
        </div>

    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Detail Produk</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Keterangan</th>
                            <th>Isi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="align-middle">
                            <td>ID</td>
                            <td>{{ $product->id }}</td>
                        </tr>

                        <tr class="align-middle">
                            <td>Nama Produk</td>
                            <td>{{ $product->prd_name }}</td>
                        </tr>

                        <tr class="align-middle">
                            <td>Harga</td>
                            <td>Rp {{ number_format($product->prd_price, 0, ',', '.') }}</td>
                        </tr>

                        <tr class="align-middle">
                            <td>Status</td>
                            <td class="{{ $product->prd_status == 'tersedia' ? 'badge bg-success' : 'badge bg-danger' }} mt-1">
                                {{ ucfirst($product->prd_status) }}
                            </td>
                        </tr>

                        <tr class="align-middle">
                            <td>Deskripsi</td>
                            <td>{{ $product->prd_description }}</td>
                        </tr>
                        <tr class="align-middle">
                            <td>Dibuat oleh</td>
                            <td>{{ $product->creator->name ?? '-' }}</td>
                        </tr>

                        <tr class="align-middle">
                            <td>Diubah oleh</td>
                            <td>{{ $product->updater->name ?? '-' }}</td>
                        </tr>
                        <tr class="align-middle">
                            <td>Dibuat pada</td>
                            <td>{{ $product->usr_created_at }}</td>
                        </tr>
                        <tr class="align-middle">
                            <td>Diubah pada</td>
                            <td>{{ $product->usr_updated_at }}</td>
                        </tr>
                        @if ($product->usr_deleted_at)
                            <tr class="align-middle">
                                <td>Dihapus pada</td>
                                <td>{{ $product->usr_deleted_at }}</td>
                            </tr>
                        @endif
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</x-layouts.admin.main>

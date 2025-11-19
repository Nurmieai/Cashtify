<x-layouts.admin.main>
    <x-slot:title>{{ $title ?? 'Ubah Produk' }}</x-slot:title>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h5>{{ session('success') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Ubah Produk</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('products.update', $product->prd_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Nama Produk --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Produk</label>
                    <input type="text" name="prd_name"
                           class="form-control @error('prd_name') is-invalid @enderror"
                           value="{{ old('prd_name', $product->prd_name) }}" required>
                    @error('prd_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Harga --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Harga Produk</label>
                    <input type="number" name="prd_price"
                           class="form-control @error('prd_price') is-invalid @enderror"
                           value="{{ old('prd_price', $product->prd_price) }}" required>
                    @error('prd_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="prd_status" class="form-select">
                        <option value="1" {{ $product->prd_status == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ $product->prd_status == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="prd_description" rows="4"
                              class="form-control @error('prd_description') is-invalid @enderror">{{ old('prd_description', $product->prd_description) }}</textarea>
                    @error('prd_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Gambar sebelumnya --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold d-block">Foto Produk</label>
                    @if ($product->prd_card_url)
                        <img src="{{ asset($product->prd_card_url) }}" alt="product image"
                             class="img-fluid mb-2 rounded shadow" style="height:150px; object-fit:contain;">
                    @endif

                    <input type="file" name="prd_card_url" class="form-control">
                </div>

                {{-- Tombol --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </form>
        </div>

        {{-- Informasi timestamp --}}
        <div class="card-footer small text-muted">
            Dibuat: {{ $product->usr_created_at ?? '-' }} |
            Diubah: {{ $product->usr_updated_at ?? '-' }} |
            Dihapus: {{ $product->usr_deleted_at ?? '-' }}
        </div>
    </div>
</x-layouts.admin.main>

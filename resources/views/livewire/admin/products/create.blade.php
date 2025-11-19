<x-layouts.admin.form>
    <x-slot name="title">Tambah Produk</x-slot>

    <div class="card card-primary card-outline mb-4">
        <div class="card-header">
            <div class="card-title">Tambah Produk Baru</div>
        </div>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card-body">

                {{-- Nama Produk --}}
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Nama Produk</label>
                    <div class="col-sm-10">
                        <input type="text" name="prd_name"
                               class="form-control @error('prd_name') is-invalid @enderror"
                               value="{{ old('prd_name') }}" required>
                        @error('prd_name') <div class="invalid-feedback text-end">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Deskripsi</label>
                    <div class="col-sm-10">
                        <textarea name="prd_description"
                                  class="form-control @error('prd_description') is-invalid @enderror"
                                  rows="3" required>{{ old('prd_description') }}</textarea>
                        @error('prd_description') <div class="invalid-feedback text-end">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Status --}}
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                        <select name="prd_status" class="form-select @error('prd_status') is-invalid @enderror">
                            <option value="tersedia">Tersedia</option>
                            <option value="tidak tersedia">Tidak Tersedia</option>
                        </select>
                        @error('prd_status') <div class="invalid-feedback text-end">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Harga --}}
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Harga</label>
                    <div class="col-sm-10">
                        <input type="number" name="prd_price"
                               class="form-control @error('prd_price') is-invalid @enderror"
                               value="{{ old('prd_price') }}" required>
                        @error('prd_price') <div class="invalid-feedback text-end">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Foto Produk --}}
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Foto Produk</label>
                    <div class="col-sm-10">
                        <input type="file" name="prd_card_url"
                               class="form-control @error('prd_card_url') is-invalid @enderror">
                        @error('prd_card_url') <div class="invalid-feedback text-end">{{ $message }}</div> @enderror
                    </div>
                </div>

            </div>

            <div class="card-footer">
                <button type="submit"
                        class="btn btn-outline-primary px-5"
                        onclick="this.disabled=true; this.form.submit();">
                    Kirim
                </button>
            </div>
        </form>
    </div>

</x-layouts.admin.form>

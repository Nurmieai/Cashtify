@extends('layouts.prf')

@section('title', 'Profil Akun')

@section('content')
<div class="container py-5">
  <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 px-4 border-bottom">
      <a href="{{ route('landing') }}" class="btn btn-light btn-sm rounded-pill px-3 shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
      </a>
      <h5 class="mb-0 fw-semibold text-danger"><i class="bi bi-person-circle me-2"></i>Profil Akun</h5>
      <div></div>
    </div>

    <div class="card-body p-4">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

      <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
        @csrf
        @method('PUT')

        <div class="text-center mb-4 position-relative">
          <div class="position-relative d-inline-block">
            <img id="previewImage"
                 src="{{ asset($user->usr_card_url ?? 'assets/images/default_user.png') }}"
                 class="rounded-circle border border-3 border-light shadow-sm object-fit-cover"
                 style="width:120px; height:120px; object-fit:cover;">
            <label for="photo" class="position-absolute bottom-0 end-0 bg-danger text-white rounded-circle p-2 shadow-sm"
                   style="cursor:pointer;">
              <i class="bi bi-camera"></i>
            </label>
            <input type="file" name="photo" id="photo" class="d-none" accept="image/*">
          </div>
        </div>

        <!-- Nama -->
        <div class="mb-3">
          <label class="form-label fw-semibold text-secondary">Nama</label>
          <input type="text" name="name" class="form-control rounded-3 shadow-sm"
                 value="{{ old('name', $user->name) }}" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label class="form-label fw-semibold text-secondary">Email</label>
          <input type="email" name="email" class="form-control rounded-3 shadow-sm"
                 value="{{ old('email', $user->email) }}" required>
        </div>

        <!-- Password -->
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold text-secondary">Password Baru</label>
            <input type="password" name="password" class="form-control rounded-3 shadow-sm"
                   placeholder="Kosongkan jika tidak diganti">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold text-secondary">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control rounded-3 shadow-sm">
          </div>
        </div>

        <!-- Tombol -->
        <div class="text-end mt-4">
          <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 shadow-sm">
            <i class="bi bi-save me-1"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>

    <!-- Footer -->
    <div class="card-footer bg-light text-end text-secondary py-2 px-4">
      Role: <strong>{{ $user->is_admin ? 'Admin' : 'Pembeli' }}</strong>
    </div>
  </div>
</div>
@endsection

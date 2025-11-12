<x-layouts.auth>
  <x-slot name="title">Register</x-slot>

  <section class="d-flex align-items-center justify-content-center" style="min-height: 100vh; background: #f8f9fa;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-11 col-sm-8 col-md-6 col-lg-4">
          <div class="card border-0 shadow-lg rounded-4 p-4" style="background: #ffffff;">
            <h4 class="fw-bold mb-3 text-center text-primary">Buat Akun Baru</h4>
            <p class="text-muted text-center mb-4">Gabung dan nikmati fitur terbaik Cashtify 💸</p>

            <form method="POST" action="{{ route('register') }}">
              @csrf

              <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" name="name" id="name"
                       class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required autofocus>
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" name="email" id="email"
                       class="form-control form-control-lg rounded-3 @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required>
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input type="password" name="password" id="password"
                       class="form-control form-control-lg rounded-3 @error('password') is-invalid @enderror"
                       required>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="form-control form-control-lg rounded-3" required>
              </div>

              <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-semibold">
                Daftar Sekarang
              </button>

              <p class="text-center mt-3 mb-0 text-muted">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-primary">
                  Masuk di sini
                </a>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</x-layouts.auth>

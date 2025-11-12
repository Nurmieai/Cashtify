<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Cashtify Login Page" />
  <title>Masuk | Cashtify</title>

  <link rel="shortcut icon" href="{{ asset('assets/images/1.svg') }}" type="image/svg+xml" />
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #f8f9fa, #e9f5ff);
      font-family: 'Poppins', sans-serif;
    }

    .login-card {
      border: none;
      border-radius: 1rem;
      background: #fff;
      box-shadow: 0 8px 25px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
    }

    .login-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 30px rgba(0,0,0,0.1);
    }

    .btn-primary {
      background: linear-gradient(135deg, #007bff, #00b4d8);
      border: none;
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #0069d9, #0096c7);
    }

    .divider {
      display: flex;
      align-items: center;
      text-align: center;
      color: #aaa;
    }

    .divider::before, .divider::after {
      content: "";
      flex: 1;
      height: 1px;
      background: #ddd;
    }

    .divider:not(:empty)::before {
      margin-right: .75em;
    }

    .divider:not(:empty)::after {
      margin-left: .75em;
    }

    .toggle-password {
      cursor: pointer;
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #888;
    }

    .social-btn {
      border: 1px solid #ddd;
      background: #fff;
      transition: 0.2s;
    }

    .social-btn:hover {
      background: #f1f5ff;
      border-color: #007bff;
    }
  </style>
</head>

<body>
  <section class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-11 col-sm-8 col-md-6 col-lg-4">
          <div class="card login-card p-4">
            <div class="text-center mb-4">
              <img src="{{ asset('assets/images/1.svg') }}" width="60" alt="Logo Cashtify">
              <h4 class="fw-bold mt-2 text-primary">Selamat Datang 👋</h4>
            </div>

            {{-- Error --}}
            @if ($errors->any())
              <div class="alert alert-danger text-center py-2">
                {{ $errors->first() }}
              </div>
            @endif

            {{-- Login Form --}}
            <form action="{{ route('login.post') }}" method="POST">
              @csrf

              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" id="email" name="email"
                       class="form-control form-control-lg rounded-3 @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="contoh@email.com" required autofocus>
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="position-relative">
                        <input type="password" id="password" name="password"
                            class="form-control form-control-lg rounded-3 pe-5 @error('password') is-invalid @enderror"
                            placeholder="••••••••" required>
                        <i class="bi bi-eye toggle-password position-absolute" id="togglePassword"
                        style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888;"></i>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    </div>

              <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-semibold">
                Masuk Sekarang
              </button>

              <p class="text-center mt-4 mb-0 text-muted">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-decoration-none fw-semibold text-primary">
                  Daftar Sekarang
                </a>
              </p>
            </form>
          </div>

          <p class="text-center mt-3">
            <a href="{{ route('landing') }}" class="text-decoration-none text-muted">
              ⟵ Kembali ke Landing Page
            </a>
          </p>
        </div>
      </div>
    </div>
  </section>

  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    togglePassword.addEventListener('click', function () {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      this.classList.toggle('bi-eye-slash');
    });
  </script>
</body>
</html>

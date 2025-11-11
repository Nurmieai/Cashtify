<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Cashtify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="text-center mb-4 fw-bold">Masuk ke Cashtify</h4>

                    {{-- Tampilkan error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger text-center py-2">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    {{-- Form login --}}
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                   value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label for="remember" class="form-check-label">Ingat saya</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Masuk
                        </button>
                    </form>
                </div>
            </div>
            <p class="text-center mt-3">
                <a href="{{ route('landing') }}" class="text-decoration-none text-muted">
                    ⟵ Kembali ke Landing Page
                </a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

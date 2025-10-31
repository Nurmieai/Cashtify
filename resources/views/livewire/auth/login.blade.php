<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Cashtify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @livewireStyles
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="text-center mb-4">Masuk ke Cashtify</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form wire:submit.prevent="login">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" wire:model="email" class="form-control" required autofocus>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" wire:model="password" class="form-control" required>
                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" wire:model="remember" class="form-check-input" id="remember">
                            <label for="remember" class="form-check-label">Ingat saya</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled">
                            <span wire:loading.remove>Masuk</span>
                            <span wire:loading>Memproses...</span>
                        </button>
                    </form>
                </div>
            </div>
            <p class="text-center mt-3">
                <a href="{{ route('landing') }}">Kembali ke Landing Page</a>
            </p>
        </div>
    </div>
</div>

@livewireScripts
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

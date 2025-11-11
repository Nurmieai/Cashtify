@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Profil Akun</h5>
            <a href="{{ route('landing') }}" class="btn btn-light btn-sm">Kembali</a>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="text-center mb-3">
                    <img src="{{ asset($user->usr_card_url ?? 'assets/images/default_user.png') }}"
                         class="rounded-circle mb-2 object-fit-cover"
                         style="width: 100px; height: 100px;">
                    <div>
                        <input type="file" name="photo" class="form-control form-control-sm w-auto d-inline">
                    </div>
                </div>

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Password Baru</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        <div class="card-footer bg-light text-muted text-end">
            Role: <strong>{{ $user->is_admin ? 'Admin' : 'User Biasa' }}</strong>
        </div>
    </div>
</div>
@endsection

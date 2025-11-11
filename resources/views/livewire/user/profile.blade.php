<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Saya - Cashtify</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #f9fafb;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .profile-container {
      background: #fff;
      width: 100%;
      max-width: 700px;
      border-radius: 20px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      padding: 2rem;
      position: relative;
    }

    .back-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      text-decoration: none;
      color: #dc3545;
      font-weight: bold;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .profile-pic {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #dc3545;
      margin-bottom: 1rem;
    }
  </style>
</head>

<body>
  <div class="profile-container">

    {{-- Tombol kembali --}}
    <a href="{{ route('landing') }}" class="back-btn">
      ← Kembali
    </a>

    <div class="text-center">
      <img
        src="{{ Auth::user()->usr_card_url ? asset(Auth::user()->usr_card_url) : asset('assets/images/default_user.png') }}"
        alt="Foto Profil"
        class="profile-pic"
      >
      <h4 class="mb-1">{{ Auth::user()->name }}</h4>
      <p class="text-muted mb-3">{{ Auth::user()->email }}</p>

      {{-- Form Update --}}
      <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="text-start">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label fw-semibold">Nama</label>
          <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Email</label>
          <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Foto Profil</label>
          <input type="file" name="usr_card_url" class="form-control">
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-danger px-4">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>

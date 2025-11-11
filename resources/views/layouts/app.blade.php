<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Cashtify')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  @livewireStyles
</head>

<body class="bg-light">

  {{-- 🔹 Navbar --}}
  @include('layouts.navbar')

  {{-- 🔹 Isi halaman --}}
  <main class="container py-4">
    @yield('content')
  </main>

  {{-- 🔹 Footer --}}
  @include('layouts.footer')

  @livewireScripts
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

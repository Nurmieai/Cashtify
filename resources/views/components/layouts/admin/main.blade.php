<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'Dashboard' }}</title>

    <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('assets/images/1.svg') }}" type="image/svg+xml" />

  <!-- Bootstrap & Icons -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.lineicons.com/4.0/lineicons.css"/>
  <link rel="stylesheet" href="{{ asset('css/tiny-slider.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/glightbox.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/landing.css') }}" />

    {{-- SB Admin CSS --}}
    <link href="{{ asset('css/admin-style/styles.css') }}" rel="stylesheet" />
    {{-- Font Awesome --}}
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @stack('styles')
</head>
<body class="sb-nav-fixed">

    {{-- Navbar --}}
    <x-layouts.admin.navbar />

    <div id="layoutSidenav">
        {{-- Sidebar --}}
        <x-layouts.admin.sidebar />

        {{-- Main Content --}}
        <div id="layoutSidenav_content">
            <main class="p-4">
                {{ $slot }}
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin-style/scripts.js') }}"></script>
    @stack('scripts')
</body>
</html>

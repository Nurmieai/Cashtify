<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'Dashboard' }}</title>

    {{-- SB Admin CSS --}}
    <link href="{{ asset('css/admin-style/styles.css') }}" rel="stylesheet" />
    {{-- Font Awesome --}}
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @stack('styles')
</head>
<body class="sb-nav-fixed">

    {{-- Navbar --}}
    <x-layouts.navbars />

    <div id="layoutSidenav">
        {{-- Sidebar --}}
        <x-layouts.sidebars />

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

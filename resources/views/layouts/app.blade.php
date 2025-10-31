<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    <title>{{ config('app.name', 'Cashtify') }}</title>

    {{-- Tambahkan CSS AdminLTE --}}
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    @livewireStyles
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    {{-- Navbar --}}
    @include('livewire.settings.navbar')

    {{-- Sidebar --}}
    @include('livewire.settings.category-bar')

    {{-- Content Wrapper --}}
    <div class="content-wrapper">
        <section class="content pt-3">
            {{ $slot }}
        </section>
    </div>

    {{-- Footer --}}
    @include('livewire.settings.footer')

</div>

{{-- JS AdminLTE --}}
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
@livewireScripts
</body>
</html>

<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">

        <div class="sb-sidenav-menu">
            <div class="nav">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ set_active('dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                {{-- Produk --}}
                <a href="{{ route('products.index') }}"
                    class="nav-link {{ set_active('products.*') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                    Produk
                </a>

                {{-- Transaksi --}}
                <a href="{{ route('transactions.index') }}"
                    class="nav-link {{ set_active('transactions.*') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-receipt"></i></div>
                    Transaksi
                </a>

                {{-- User --}}
                <a href="{{ route('users.index') }}"
                    class="nav-link {{ set_active('users.*') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    User
                </a>

                {{-- Post --}}
                <a href="{{ route('posts.index') }}"
                    class="nav-link {{ set_active('posts.*') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-newspaper"></i></div>
                    Posting
                </a>

                {{-- Lokasi --}}
                <a href="{{ route('locations.index') }}"
                    class="nav-link {{ set_active('locations.*') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-map-marker-alt"></i></div>
                    Lokasi
                </a>

                {{-- Akuntansi --}}
                <a href="{{ route('accounting.index') }}"
                    class="nav-link {{ set_active('accounting.*') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-calculator"></i></div>
                    Akuntansi
                </a>

            </div>
        </div>

    </nav>
</div>

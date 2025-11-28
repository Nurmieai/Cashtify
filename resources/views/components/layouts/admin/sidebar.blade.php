<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">

        <div class="sb-sidenav-menu">
            <div class="nav">

                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ set_active('dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <a href="{{ route('products.adminIndex') }}"
                    class="nav-link {{ set_active('products.*') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                    Produk
                </a>

                <a href="{{ route('transactions.index') }}"
                    class="nav-link {{ set_active('transactions.*') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-receipt"></i></div>
                    Transaksi
                </a>

                {{-- FIXED: pakai "admin.users" biar match --}}
                <a href="{{ route('admin.users') }}"
                    class="nav-link {{ set_active('admin.users') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Data Pengguna
                </a>

                <a href="{{ route('accounting.index') }}"
                    class="nav-link {{ set_active('accounting.*') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-calculator"></i></div>
                    Akuntansi
                </a>
            </div>
        </div>

    </nav>
</div>

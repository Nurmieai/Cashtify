<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">

            <div class="nav">

                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <!-- Produk -->
                <a href="{{ route('products.index') }}" class="nav-link">
                    <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                    Produk
                </a>

                <!-- Transaksi -->
                <a href="{{ route('transactions.index') }}" class="nav-link">
                    <div class="sb-nav-link-icon"><i class="fas fa-receipt"></i></div>
                    Transaksi
                </a>

                <!-- User / Pembeli -->
                <a href="{{ route('users.index') }}" class="nav-link">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    User
                </a>

            </div>
        </div>
    </nav>
</div>

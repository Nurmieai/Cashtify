<section class="navbar-area navbar-nine py-3">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-12">
        <nav class="navbar navbar-expand-lg">
          <!-- Logo -->
          <a class="navbar-brand d-flex align-items-center" href="{{ route('landing') }}">
            <div class="logo-wrapper">
              <img src="{{ asset('assets/images/2.svg') }}" alt="Logo" class="logo" style="margin-right: 100px;" />
            </div>
          </a>

          <!-- Toggler -->
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNine"
            aria-controls="navbarNine" aria-expanded="false" aria-label="Toggle navigation">
            <span class="toggler-icon"></span>
            <span class="toggler-icon"></span>
            <span class="toggler-icon"></span>
          </button>

          <!-- Navbar Menu -->
          <div class="collapse navbar-collapse sub-menu-bar" id="navbarNine">
            <ul class="navbar-nav mb-2 mb-lg-0">
              <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
              <li class="nav-item"><a class="nav-link" href="#blog">Features</a></li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button"
                   data-bs-toggle="dropdown" aria-expanded="false">Menu</a>
                <ul class="dropdown-menu" aria-labelledby="menuDropdown">
                  <li><a class="dropdown-item" href="#call-action">Transaksi</a></li>
                  <li><a class="dropdown-item" href="#pricing">Produk</a></li>
                  <li><a class="dropdown-item" href="#blog">Pencatatan</a></li>
                </ul>
              </li>
              <li class="nav-item"><a class="nav-link" href="#clients">Maps</a></li>
            </ul>

            <!-- Buttons -->
            <div class="nav-actions d-flex align-items-center gap-2 flex-wrap ms-auto">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm fw-semibold">LOGIN</a>
                    <a href="{{ route('register') }}" class="btn btn-light text-danger btn-sm fw-semibold">SIGN UP</a>
                @endguest

                @auth
                    @php
                        $user = Auth::user();
                        $profileImage = $user->usr_card_url
                            ? asset($user->usr_card_url)
                            : asset('assets/images/default_user.png');
                    @endphp

                    <div class="dropdown">
                        <a class="btn btn-outline-light btn-sm fw-semibold dropdown-toggle d-flex align-items-center gap-2 px-3 py-1"
                        href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset(Auth::user()->usr_card_url ?? 'assets/images/header/user_placeholder.jpg') }}"
                                class="rounded-circle shadow-sm object-fit-cover border border-light"
                                width="32" height="32"
                                style="object-fit: cover;">
                            <span class="text-uppercase small">{{ Auth::user()->name }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2"
                            aria-labelledby="userDropdown" style="min-width: 180px; border-radius: 10px;">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile') }}">
                                    <i class="bi bi-person-circle"></i> Profil Saya
                                </a>
                            </li>

                            @if (Auth::user()->hasRole('admin'))
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                                        <i class="bi bi-speedometer2 text-muted"></i> Dashboard Admin
                                    </a>
                                </li>
                            @endif

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="dropdown-item d-flex align-items-center gap-2 fw-semibold">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
          </div>
        </nav>
      </div>
    </div>
  </div>
</section>

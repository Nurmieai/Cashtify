<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="Cashtify Landing Page" />
  <title>Cashtify | @yield('title', 'Landing Page')</title>

  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('assets/images/1.svg') }}" type="image/svg+xml" />

  <!-- Bootstrap & Icons -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.lineicons.com/4.0/lineicons.css"/>
  <link rel="stylesheet" href="{{ asset('css/tiny-slider.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/glightbox.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/landing.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/form.css') }}" />
  <link rel="stylesheet" href="{{ asset('/css/adminlte.min.css') }}">
</head>
<body class="d-flex flex-column min-vh-100">
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
                        <li class="nav-item"><a class="nav-link active" href="/">Produk</a></li>
                        @if (Auth::check() && Auth::user()->hasRole('Pembeli'))
                        <li class="nav-item dropdown ">
                        <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Menu</a>
                        <ul class="dropdown-menu shadow p-3 mb-5 bg-body-tertiary" aria-labelledby="menuDropdown">
                            <li><a class="dropdown-item" href="{{ route('orders') }}">Pesanan Saya</a></li>
                            <li><a class="dropdown-item" href="{{ route('cart') }}">Keranjang Saya</a></li>
                            <li><a class="dropdown-item" href="#Notification">Notifikasi</a></li>
                        </ul>
                        </li>
                        @endif
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
                                        : asset('assets/images/header/user_placeholder.jpg');
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
                                                <i class="bi bi-person-circle"></i>Profil Saya
                                            </a>
                                        </li>

                                        @if (Auth::user()->hasRole('admin'))
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                                                    <i class="bi bi-speedometer2 text-muted"></i>Dashboard Admin
                                                </a>
                                            </li>
                                        @endif

                                        <li><hr class="dropdown-divider"></li>

                                        <li>
                                            <form action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="dropdown-item d-flex align-items-center gap-2 fw-semibold">
                                                    <i class="bi bi-box-arrow-right"></i> Keluar
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

    <main class="flex-fill">
        {{ $slot }}
    </main>

    <x-layouts.footer />

  @stack('scripts')
  <!--====== js ======-->
  <script src="{{ asset('js/glightbox.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/main.js') }}"></script>
  <script src="{{ asset('js/tiny-slider.js') }}"></script>

  <script>

    //===== close navbar-collapse when a  clicked
    let navbarTogglerNine = document.querySelector(
      ".navbar-nine .navbar-toggler"
    );
    navbarTogglerNine.addEventListener("click", function () {
      navbarTogglerNine.classList.toggle("active");
    });

    // ==== left sidebar toggle
    let sidebarLeft = document.querySelector(".sidebar-left");
    let overlayLeft = document.querySelector(".overlay-left");
    let sidebarClose = document.querySelector(".sidebar-close .close");

    overlayLeft.addEventListener("click", function () {
      sidebarLeft.classList.toggle("open");
      overlayLeft.classList.toggle("open");
    });
    sidebarClose.addEventListener("click", function () {
      sidebarLeft.classList.remove("open");
      overlayLeft.classList.remove("open");
    });

    // ===== navbar nine sideMenu
    let sideMenuLeftNine = document.querySelector(".navbar-nine .menu-bar");

    sideMenuLeftNine.addEventListener("click", function () {
      sidebarLeft.classList.add("open");
      overlayLeft.classList.add("open");
    });

    //========= glightbox
    GLightbox({
      'href': 'https://www.youtube.com/watch?v=r44RKWyfcFw&fbclid=IwAR21beSJORalzmzokxDRcGfkZA1AtRTE__l5N4r09HcGS5Y6vOluyouM9EM',
      'type': 'video',
      'source': 'youtube', //vimeo, youtube or local
      'width': 900,
      'autoplayVideos': true,
    });

  </script>
</body>

</html>

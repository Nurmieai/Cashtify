<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark shadow p-3 mb-5 bg-body-tertiary">
    <!-- Navbar Brand -->
    <a class="navbar-brand ps-3" href="#">Cashtify - Admin</a>

    <!-- Sidebar Toggle -->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0"
            id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>


    <!-- Right Side -->
    <ul class="navbar-nav ms-auto me-3 me-lg-4">

        @php
            $user = Auth::user();
            $profileImage = $user && $user->usr_card_url
                ? asset($user->usr_card_url)
                : asset('assets/images/default_user.png');
        @endphp

        <li class="nav-item dropdown">

            <!-- Trigger -->
            <a class="nav-link dropdown-toggle d-flex align-items-center"
               id="userDropdown"
               href="#"
               role="button"
               data-bs-toggle="dropdown"
               aria-expanded="false">

                <img src="{{ asset(Auth::user()->usr_card_url ?? 'assets/images/header/user_placeholder.jpg') }}"
                class="rounded-circle shadow-sm object-fit-cover border border-light me-2"
                width="32" height="32"
                style="object-fit: cover;">

                <strong>{{ Auth::user()->name }}</strong>
            </a>

            <!-- Dropdown -->
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                <li><a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile') }}">
                    <i class="bi bi-person-circle"></i>Profil Saya
                    </a>
                </li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="dropdown-item d-flex align-items-center gap-2">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </button>
                </form>
            </ul>
        </li>
    </ul>
</nav>

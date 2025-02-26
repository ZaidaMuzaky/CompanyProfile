<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

        <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
            <h1 class="sitename">FlexStart</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="#home" class="active">Home</a></li>
                <li><a href="#tentang">Tentang Perusahaan</a></li>
                <li><a href="#produk">Produk</a></li>
                <li><a href="#footer">Informasi Lainnya</a></li>
                <li><a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
    </div>
</header>

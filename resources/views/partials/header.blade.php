<header id="header" class="header d-flex align-items-center fixed-top" style="background-color: #f8f9fa;">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

        <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <img src="{{ asset('assets/img/Logo-Utama.png') }}" alt="Logo" style="width:230px; height: auto;">
            {{-- <h1>Hasta Panca Mandiri Utama</h1> --}}
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ url('/') }}" class="active">Home</a></li>
                <li class="dropdown"><a href="#"><span>Living at HPMU</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a> 
                <ul>
                    <li><a href="{{ route('people') }}">Living With Our People</a></li>
                    <li><a href="{{ route('community') }}">Living With Our Community</a></li>
                </ul>
                </li>
                <li><a href="{{ route('newsvisit.index') }}">News</a></li>
                <li><a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
    </div>
</header>

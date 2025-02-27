<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ url('/') }}" class="logo d-flex align-items-center">
            <img src="{{ asset('assets2/img/logo.png') }}" alt="">
            <span class="d-none d-lg-block">NiceAdmin</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
    <div class="ms-auto d-flex align-items-center">
        <span class="me-3">{{ Auth::user()->username }}</span>
    </div>
</header><!-- End Header -->

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link " href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i> <span>Dashboard</span>
            </a>
        </li>
        @if (Auth::check() && Auth::user()->type === 'admin')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.users') }}">
                    <i class="bi bi-people"></i> <span>Managemen Users</span>
                </a>
            </li>
        @endif
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                @csrf
                <button type="submit" class="nav-link btn btn-link" style="color: inherit; text-decoration: none;">
                    <i class="bi bi-box-arrow-in-right"></i> <span>Logout</span>
                </button>
            </form>
        </li>
    </ul>
</aside><!-- End Sidebar -->

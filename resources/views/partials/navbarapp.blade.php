<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
            <img src="{{ asset('assets/img/Asset-9.png') }}" alt="" style="width: 150px; height: auto;">
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
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}"
                href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i> <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user.files.*') ? '' : 'collapsed' }}"
                href="{{ route('user.files.index') }}">
                <i class="bi bi-file-earmark-pdf"></i> <span>Managemen Files PDF</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ config('app.google_form_link', 'https://forms.gle/') }}"
                target="_blank">
                <i class="bi bi-google"></i> <span>Google Form</span>
            </a>
        </li>
        @if (Auth::check() && Auth::user()->type === 'admin')
            <li class="nav-item">
                <span class="nav-link">Admin</span>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users') ? '' : 'collapsed' }}"
                    href="{{ route('admin.users') }}">
                    <i class="bi bi-people"></i> <span>Managemen Users</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.folders.*') ? '' : 'collapsed' }}"
                    href="{{ route('admin.folders.index') }}">
                    <i class="bi bi-folder"></i> <span>Managemen Folders</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.google-form.*') ? '' : 'collapsed' }}"
                    href="{{ route('admin.google-form.edit') }}">
                    <i class="bi bi-pencil"></i> <span>Edit Google Form Link</span>
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

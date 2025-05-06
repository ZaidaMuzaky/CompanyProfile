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
        @if (Auth::check() && Auth::user()->type === 'admin')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}"
                    href="{{ route('dashboard') }}">
                    <i class="bi bi-grid"></i> <span>Dashboard</span>
                </a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user.newsview.*') ? '' : 'collapsed' }}"
                href="{{ route('user.newsview.index') }}">
                <i class="bi bi-newspaper"></i> <span>Berita</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user.files.*') ? '' : 'collapsed' }}"
                href="{{ route('user.files.index') }}">
                <i class="bi bi-file-earmark-pdf"></i> <span>Managemen Files PDF</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user.gform.index') ? '' : 'collapsed' }}"
                href="{{ route('user.gform.index') }}">
                <i class="bi bi-google"></i> <span>Google Form</span>
            </a>
        </li>

        @php
$menus = \App\Models\Menu::with('submenus')->get();
        @endphp

        @if ($menus->count() > 0)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#menu-database" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-folder"></i> <span>KPI Plant</span> <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="menu-database" class="nav-content collapse {{ request()->routeIs('menus.view') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                    @foreach ($menus as $menu)
                        <li>
                            <a href="{{ route('menus.view', $menu->id_menu) }}" class="{{ request()->routeIs('menus.view', $menu->id_menu) ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>{{ $menu->nama }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif

        @if (Auth::check() && Auth::user()->type === 'admin')
            {{-- admin side --}}
            <li class="nav-item">
                <span class="nav-link text-muted" style="font-size: 0.9rem; font-weight: bold; text-transform: uppercase;">
                    Admin Menu
                </span>
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
                        <i class="bi bi-folder"></i> <span>Managemen Section</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.google-form.*') ? '' : 'collapsed' }}"
                        href="{{ route('admin.google-form.edit') }}">
                        <i class="bi bi-pencil"></i> <span>Edit Google Form Link</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.menus.*') ? '' : 'collapsed' }}"
                        href="{{ route('admin.menus.index') }}">
                        <i class="bi bi-folder"></i> <span>Managemen Meca</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.news.*') ? '' : 'collapsed' }}"
                        href="{{ route('admin.news.index') }}">
                        <i class="bi bi-newspaper"></i> <span>Managemen Berita</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.achievement.*') ? '' : 'collapsed' }}"
                        href="{{ route('admin.achievement.index') }}">
                        <i class="bi bi-award"></i> <span>Managemen Penghargaan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.community.*') ? '' : 'collapsed' }}"
                        href="{{ route('admin.community.index') }}">
                        <i class="bi bi-people"></i> <span>Managemen Poster</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.people.*') ? '' : 'collapsed' }}"
                        href="{{ route('admin.people.index') }}">
                        <i class="bi bi-people"></i> <span>Managemen Our People</span>
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

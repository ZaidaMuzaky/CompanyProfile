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
            <a class="nav-link {{ request()->routeIs('user.files.*') ? '' : 'collapsed' }}"
                href="{{ route('user.files.index') }}">
                <i class="bi bi-file-earmark-pdf"></i> <span>Managemen Files PDF</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user.newsview.*') ? '' : 'collapsed' }}"
                href="{{ route('user.newsview.index') }}">
                <i class="bi bi-newspaper"></i> <span>Berita</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user.gform.index') ? '' : 'collapsed' }}"
                href="{{ route('user.gform.index') }}">
                <i class="bi bi-google"></i> <span>Google Form</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#backlog-submenu" aria-expanded="false">
                <i class="bi bi-journal-check"></i> <span>Backlog</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="backlog-submenu" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('user.backlog.index') }}">
                        <i class="bi bi-bar-chart-line"></i><span>Semua Status Form</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.backlog.show') }}">
                        <i class="bi bi-bar-chart-line"></i><span>Status Form Saya</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.backlog.form') }}">
                        <i class="bi bi-pencil-square"></i><span>Isi Form</span>
                    </a>
                </li>
            </ul>
        </li>





        @php
            $mainMenus = \App\Models\MainMenu::with('menuSections.brands.files')->get();
        @endphp

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#pareto-problem-unit" data-bs-toggle="collapse"
                href="#">
                <i class="bi bi-diagram-3"></i> <span>Pareto Problem Unit</span> <i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="pareto-problem-unit" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                @foreach ($mainMenus as $mainMenu)
                    <li>
                        <a data-bs-toggle="collapse" href="#menu-{{ $mainMenu->id }}" class="nav-link collapsed">
                            <i class="bi bi-folder"></i><span>{{ $mainMenu->nama }}</span>
                        </a>
                        <ul id="menu-{{ $mainMenu->id }}" class="nav-content collapse ms-4">
                            @foreach ($mainMenu->menuSections as $section)
                                <li>
                                    <a data-bs-toggle="collapse" href="#section-{{ $section->id }}"
                                        class="nav-link collapsed">
                                        <i class="bi bi-chevron-right"></i><span>{{ $section->nama }}</span>
                                    </a>
                                    <ul id="section-{{ $section->id }}" class="nav-content collapse ms-4">
                                        @foreach ($section->brands as $brand)
                                            <li>
                                                <a href="{{ route('user.pareto.index', $brand->id) }}"
                                                    class="{{ request()->routeIs('user.pareto.index') && request()->route('menuBrand') == $brand->id ? 'active' : '' }}">
                                                    <i class="bi bi-circle"></i>{{ $brand->nama }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </li>

        @php
            $menus = \App\Models\Menu::with('submenus')->get();
        @endphp

        @if ($menus->count() > 0)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#menu-database" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-folder"></i> <span>KPI Plant</span> <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="menu-database"
                    class="nav-content collapse {{ request()->routeIs('menus.view') ? 'show' : '' }}"
                    data-bs-parent="#sidebar-nav">
                    @foreach ($menus as $menu)
                        <li>
                            <a href="{{ route('menus.view', $menu->id_menu) }}"
                                class="{{ request()->routeIs('menus.view', $menu->id_menu) ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>{{ $menu->nama }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif
        @php
            $audits = \App\Models\Audit::with('uploads')->get();
        @endphp
        @if ($audits->count() > 0)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#menu-audit" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-folder-check"></i> <span>Audit Service</span> <i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="menu-audit" class="nav-content collapse {{ request()->routeIs('audit.view') ? 'show' : '' }}"
                    data-bs-parent="#sidebar-nav">
                    @foreach ($audits as $audit)
                        <li>
                            <a href="{{ route('audit.view', $audit->id) }}"
                                class="{{ request()->routeIs('audit.view') && request()->route('id') == $audit->id ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>{{ $audit->nama }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif


        @if (Auth::check() && Auth::user()->type === 'admin')
            {{-- admin side --}}
            <li class="nav-item">
                <span class="nav-link text-muted"
                    style="font-size: 0.9rem; font-weight: bold; text-transform: uppercase;">
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
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.pareto.*') ? '' : 'collapsed' }}"
                    href="{{ route('admin.pareto.index') }}">
                    <i class="bi bi-diagram-3"></i> <span>Pareto Problem Unit</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#backlog-admin" aria-expanded="false">
                    <i class="bi bi-clipboard-data"></i> <span>Backlog Admin</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="backlog-admin" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('admin.backlog.form-status') }}">
                            <i class="bi bi-bar-chart-line"></i><span>Status Form</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.approvals') }}">
                            <i class="bi bi-person-check"></i><span>Supervisor Approval</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.backlog.backlog-header') }}">
                            <i class="bi bi-image"></i><span>Edit Gambar Form</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.audit.*') ? '' : 'collapsed' }}"
                    href="{{ route('admin.audit.index') }}">
                    <i class="bi bi-check2-square"></i> <span>Audit Service Management</span>
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

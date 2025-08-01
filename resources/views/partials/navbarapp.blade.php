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
            <a class="nav-link {{ request()->routeIs('user.cn-units.index') ? '' : 'collapsed' }}"
                href="{{ route('user.cn-units.index') }}">            
                <i class="bi bi-geo-alt"></i> <span>GIS CN Unit</span>
            </a>
        </li>
        
        @php
        $mainMenus = \App\Models\MainMenu::with('menuSections.brands.files')->get();
        $menus = \App\Models\Menu::with('submenus')->get();
        $audits = \App\Models\Audit::with('uploads')->get();
    
        // Deteksi apakah ada submenu aktif
        $isGeneralOpen = request()->routeIs('user.newsview.*') || request()->routeIs('user.gform.index') ||
                         request()->routeIs('user.pareto.index') || request()->routeIs('menus.view') ||
                         request()->routeIs('audit.view');
    @endphp
    
    <!-- GENERAL INFORMATION PLANT -->
    <li class="nav-item">
        <a class="nav-link {{ $isGeneralOpen ? '' : 'collapsed' }}" data-bs-target="#general-info-plant" data-bs-toggle="collapse" href="#">
            <i class="bi bi-building"></i> <span>General Information Plant</span> <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="general-info-plant" class="nav-content collapse {{ $isGeneralOpen ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
    
            <!-- BERITA -->
            <li>
                <a href="{{ route('user.newsview.index') }}"
                   class="nav-link {{ request()->routeIs('user.newsview.*') ? 'active' : '' }}">
                    <i class="bi bi-newspaper"></i> <span>Berita</span>
                </a>
            </li>
    
            <!-- GOOGLE FORM -->
            <li>
                <a href="{{ route('user.gform.index') }}"
                   class="nav-link {{ request()->routeIs('user.gform.index') ? 'active' : '' }}">
                    <i class="bi bi-google"></i> <span>Google Form</span>
                </a>
            </li>
    
            <!-- PARETO PROBLEM UNIT -->
            <li>
                <a class="nav-link collapsed" data-bs-target="#pareto-problem-unit" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-diagram-3"></i> <span>Pareto Problem Unit</span> <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="pareto-problem-unit" class="nav-content collapse" data-bs-parent="#general-info-plant">
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
    
            <!-- KPI PLANT -->
            @if ($menus->count() > 0)
                <li>
                    <a class="nav-link collapsed" data-bs-target="#menu-database" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-folder"></i> <span>KPI Plant</span> <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="menu-database" class="nav-content collapse {{ request()->routeIs('menus.view') ? 'show' : '' }}"
                        data-bs-parent="#general-info-plant">
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
    
            <!-- AUDIT SERVICE -->
            @if ($audits->count() > 0)
                <li>
                    <a class="nav-link collapsed" data-bs-target="#menu-audit" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-folder-check"></i> <span>Audit Service</span> <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="menu-audit" class="nav-content collapse {{ request()->routeIs('audit.view') ? 'show' : '' }}"
                        data-bs-parent="#general-info-plant">
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
    
        </ul>
    </li>
        {{-- <li class="nav-item">
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
        </li> --}}

        {{-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#inspection-submenu" aria-expanded="false">
                <i class="bi bi-tools"></i> <span>Backlog / Inspection After Repair</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="inspection-submenu" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('user.inspection.index') }}">
                        <i class="bi bi-files-alt"></i><span>Semua Status Form</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.inspection.show') }}">
                        <i class="bi bi-person-lines-fill"></i><span>Status Form Saya</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.inspection.form') }}">
                        <i class="bi bi-journal-plus"></i><span>Isi Form</span>
                    </a>
                </li>
            </ul>
        </li> --}}
    
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
                    <i class="bi bi-people"></i> <span>Manajemen Users</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.folders.*') ? '' : 'collapsed' }}"
                    href="{{ route('admin.folders.index') }}">
                    <i class="bi bi-folder"></i> <span>Manajemen Section</span>
                </a>
            </li>

            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.achievement.*') ? '' : 'collapsed' }}"
                    href="{{ route('admin.achievement.index') }}">
                    <i class="bi bi-award"></i> <span>Manajemen Penghargaan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.community.*') ? '' : 'collapsed' }}"
                    href="{{ route('admin.community.index') }}">
                    <i class="bi bi-people"></i> <span>Manajemen Poster</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.people.*') ? '' : 'collapsed' }}"
                    href="{{ route('admin.people.index') }}">
                    <i class="bi bi-people"></i> <span>Manajemen Our People</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.cn-units.*') ? '' : 'collapsed' }}"
                    href="{{ route('admin.cn-units.index') }}">
                    <i class="bi bi-geo-alt-fill"></i> <span>GIS Manajemen</span>
                </a>
            </li>
            @php
                $isAdminOpen = request()->routeIs('admin.google-form.*') ||
                            request()->routeIs('admin.menus.*') ||
                            request()->routeIs('admin.news.*') ||
                            request()->routeIs('admin.pareto.*') ||
                            request()->routeIs('admin.audit.*');
            @endphp

            <!-- GENERAL INFORMATION PLANT ADMIN -->
            <li class="nav-item">
                <a class="nav-link {{ $isAdminOpen ? '' : 'collapsed' }}" data-bs-target="#general-info-plant-admin"
                data-bs-toggle="collapse" href="#">
                    <i class="bi bi-building-gear"></i> <span>General Information Plant Admin</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="general-info-plant-admin" class="nav-content collapse {{ $isAdminOpen ? 'show' : '' }}"
                    data-bs-parent="#sidebar-nav">

                    <li>
                        <a class="nav-link {{ request()->routeIs('admin.google-form.*') ? 'active' : 'collapsed' }}"
                        href="{{ route('admin.google-form.edit') }}">
                            <i class="bi bi-pencil"></i> <span>Edit Google Form Link</span>
                        </a>
                    </li>

                    <li>
                        <a class="nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : 'collapsed' }}"
                        href="{{ route('admin.menus.index') }}">
                            <i class="bi bi-folder"></i> <span>Manajemen Meca</span>
                        </a>
                    </li>

                    <li>
                        <a class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : 'collapsed' }}"
                        href="{{ route('admin.news.index') }}">
                            <i class="bi bi-newspaper"></i> <span>Manajemen Berita</span>
                        </a>
                    </li>

                    <li>
                        <a class="nav-link {{ request()->routeIs('admin.pareto.*') ? 'active' : 'collapsed' }}"
                        href="{{ route('admin.pareto.index') }}">
                            <i class="bi bi-diagram-3"></i> <span>Pareto Problem Unit</span>
                        </a>
                    </li>

                    <li>
                        <a class="nav-link {{ request()->routeIs('admin.audit.*') ? 'active' : 'collapsed' }}"
                        href="{{ route('admin.audit.index') }}">
                            <i class="bi bi-check2-square"></i> <span>Audit Service Management</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- <li class="nav-item">
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
            </li> --}}
            {{-- <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#inspection-admin"
                    aria-expanded="false">
                    <i class="bi bi-shield-check"></i> <span>Backlog / Inspection After Repair</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="inspection-admin" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('admin.inspection.form-show') }}">
                            <i class="bi bi-file-earmark-text"></i><span>Status Form</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.inspection.index') }}">
                            <i class="bi bi-person-check"></i><span>Supervisor Approval</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.inspection.header.edit') }}">
                            <i class="bi bi-image"></i><span>Edit Gambar</span>
                        </a>
                    </li>
                </ul>
            </li> --}}

          
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

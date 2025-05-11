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
        @php
$mainMenus = \App\Models\MainMenu::with('menuSections.brands.files')->get();
        @endphp
        
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#pareto-problem-unit" data-bs-toggle="collapse" href="#">
                <i class="bi bi-diagram-3"></i> <span>Pareto Problem Unit</span> <i class="bi bi-chevron-down ms-auto"></i>
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
                                    <a data-bs-toggle="collapse" href="#section-{{ $section->id }}" class="nav-link collapsed">
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
$categories = \App\Models\Category::with('subcategories')->get();
        @endphp
        
        @if ($categories->count() > 0)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#menu-category" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-folder"></i> <span>Data GMM</span> <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="menu-category" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li class="nav-item">
                        <a href="{{ route('user.partunschedule.index') }}"
                            class="nav-link {{ request()->routeIs('user.partunschedule.index') ? 'active' : '' }}">
                            <i class="bi bi-wrench-adjustable-circle"></i>
                            <span>Part Unschedule</span>
                        </a>
                    </li>

                    @foreach ($categories as $category)
                        <li>
                            <a data-bs-toggle="collapse" href="#subcategory-{{ $category->id }}" class="nav-link collapsed">
                                <i class="bi bi-chevron-right"></i><span>{{ $category->name }}</span>
                            </a>
                            @if ($category->subcategories->count() > 0)
                                <ul id="subcategory-{{ $category->id }}" class="nav-content collapse ms-4">
                                    @foreach ($category->subcategories as $subcategory)
                                        <li>
                                            <a href="{{ route('user.parts.index', $subcategory->id) }}"
                                                class="{{ request()->routeIs('user.parts.index') && request()->route('id') == $subcategory->id ? 'active' : '' }}">
                                                <i class="bi bi-circle"></i>{{ $subcategory->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif


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
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.pareto.*') ? '' : 'collapsed' }}"
                        href="{{ route('admin.pareto.index') }}">
                        <i class="bi bi-diagram-3"></i> <span>Pareto Problem Unit</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.parts.*') ? '' : 'collapsed' }}"
                        href="{{ route('admin.parts.index') }}">
                        <i class="bi bi-tools"></i> <span>Manage Parts</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.partunschedule.*') ? '' : 'collapsed' }}"
                        href="{{ route('admin.partunschedule.index') }}">
                        <i class="bi bi-calendar-x"></i> <span>Manage Part Unschedule</span>
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

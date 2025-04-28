@extends('layouts.logapp')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Total Folders Card -->
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card info-card sales-card shadow-sm hover-effect">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Total Section</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                data-bs-toggle="tooltip" title="Total Folders">
                                <i class="bi bi-folder"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="fs-4">{{ $totalFolders }}</h6>
                                <span class="text-muted small pt-2 ps-1">Total number of folders</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Subfolders Card -->
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card info-card sales-card shadow-sm hover-effect">
                    <div class="card-body">
                        <h5 class="card-title text-success">Total Unit</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white"
                                data-bs-toggle="tooltip" title="Total Subfolders">
                                <i class="bi bi-folder2"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="fs-4">{{ $totalSubfolders }}</h6>
                                <span class="text-muted small pt-2 ps-1">Total number of subfolders</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Files Card -->
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card info-card sales-card shadow-sm hover-effect">
                    <div class="card-body">
                        <h5 class="card-title text-info">Total Files</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white"
                                data-bs-toggle="tooltip" title="Total Files">
                                <i class="bi bi-file-earmark"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="fs-4">{{ $totalFiles }}</h6>
                                <span class="text-muted small pt-2 ps-1">Total number of files</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Online/Offline Card -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card recent-sales overflow-auto shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">User Status <span>| Realtime</span></h5>

                        <div class="row mb-3">
                            <!-- Online Users -->
                            <div class="col-md-6">
                                <h6 class="fs-5">Online Users</h6>
                                <h6 class="fs-4">{{ $totalOnlineUsers }}</h6>
                            </div>
                            <!-- Offline Users -->
                            <div class="col-md-6">
                                <h6 class="fs-5">Offline Users</h6>
                                <h6 class="fs-4">{{ $totalOfflineUsers }}</h6>
                            </div>
                        </div>

                        <div class="mt-3">
                            <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center">
                                <label for="per_page" class="me-2 mb-0">Show</label>
                                <select name="per_page" id="per_page" class="form-select d-inline w-auto me-2"
                                    onchange="this.form.submit()">
                                    @for ($i = 5; $i <= 50; $i += 5)
                                        <option value="{{ $i }}" {{ $perPage == $i ? 'selected' : '' }}>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                                <span class="ms-2">entries</span>
                            </form>
                        </div>

                        <table class="table table-borderless datatable mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($displayedUsers as $index => $user)
                                    <tr>
                                        <th scope="row">{{ $index + 1 + ($currentPage - 1) * $perPage }}</th>
                                        <td>{{ $user->username }}</td>
                                        <td>
                                            <span class="badge {{ $user->is_online ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $user->is_online ? 'Online' : 'Offline' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-4">
                            @php
                                $totalPages = ceil($totalUsers / $perPage);
                            @endphp
                            <ul class="pagination pagination-sm">
                                <!-- Previous Button -->
                                <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                                    <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
                                        <input type="hidden" name="per_page" value="{{ $perPage }}">
                                        <button class="page-link" name="page" value="{{ max(1, $currentPage - 1) }}" {{ $currentPage == 1 ? 'disabled' : '' }}>
                                            <i class="bi bi-chevron-left"></i> Previous
                                        </button>
                                    </form>
                                </li>

                                <!-- Page Numbers -->
                                @for ($page = 1; $page <= $totalPages; $page++)
                                    <li class="page-item {{ $page == $currentPage ? 'active' : '' }}">
                                        <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
                                            <input type="hidden" name="per_page" value="{{ $perPage }}">
                                            <button class="page-link" name="page"
                                                value="{{ $page }}">{{ $page }}</button>
                                        </form>
                                    </li>
                                @endfor

                                <!-- Next Button -->
                                <li class="page-item {{ $currentPage * $perPage >= $totalUsers ? 'disabled' : '' }}">
                                    <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
                                        <input type="hidden" name="per_page" value="{{ $perPage }}">
                                        <button class="page-link" name="page" value="{{ $currentPage + 1 }}" {{ $currentPage * $perPage >= $totalUsers ? 'disabled' : '' }}>
                                            Next <i class="bi bi-chevron-right"></i>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Logged In Today Card -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card recent-sales overflow-auto shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Users yang Login Hari ini</h5>
                        <div class="mt-3">
                            <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center mb-3">
                                <input type="text" name="search_logged_in" id="search_logged_in" class="form-control w-auto me-2"
                                    value="{{ request('search_logged_in') }}" placeholder="Search by username...">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                            <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center">
                                <label for="per_page_logged_in" class="me-2 mb-0">Show</label>
                                <select name="per_page_logged_in" id="per_page_logged_in"
                                    class="form-select d-inline w-auto me-2" onchange="this.form.submit()">
                                    @for ($i = 5; $i <= 50; $i += 5)
                                        <option value="{{ $i }}"
                                            {{ $perPageLoggedIn == $i ? 'selected' : '' }}>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                                <span class="ms-2">entries</span>
                            </form>
                        </div>

                        <table class="table table-borderless datatable mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Last Login</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($displayedLoggedInTodayUsers as $index => $user)
                                    <tr>
                                        <th scope="row">
                                            {{ $index + 1 + ($currentLoggedInPage - 1) * $perPageLoggedIn }}</th>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->last_login_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-4">
                            @php
                                $totalLoggedInPages = ceil($totalLoggedInTodayUsers / $perPageLoggedIn);
                            @endphp
                            <ul class="pagination pagination-sm">
                                <!-- Previous Button -->
                                <li class="page-item {{ $currentLoggedInPage == 1 ? 'disabled' : '' }}">
                                    <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
                                        <input type="hidden" name="per_page_logged_in" value="{{ $perPageLoggedIn }}">
                                        <button class="page-link" name="logged_in_page" value="{{ max(1, $currentLoggedInPage - 1) }}" {{ $currentLoggedInPage == 1 ? 'disabled' : '' }}>
                                            <i class="bi bi-chevron-left"></i> Previous
                                        </button>
                                    </form>
                                </li>

                                <!-- Page Numbers -->
                                @for ($page = 1; $page <= $totalLoggedInPages; $page++)
                                    <li class="page-item {{ $page == $currentLoggedInPage ? 'active' : '' }}">
                                        <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
                                            <input type="hidden" name="per_page_logged_in"
                                                value="{{ $perPageLoggedIn }}">
                                            <button class="page-link" name="logged_in_page"
                                                value="{{ $page }}">{{ $page }}</button>
                                        </form>
                                    </li>
                                @endfor

                                <!-- Next Button -->
                                <li
                                    class="page-item {{ $currentLoggedInPage * $perPageLoggedIn >= $totalLoggedInTodayUsers ? 'disabled' : '' }}">
                                    <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
                                        <input type="hidden" name="per_page_logged_in" value="{{ $perPageLoggedIn }}">
                                        <button class="page-link" name="logged_in_page" value="{{ $currentLoggedInPage + 1 }}" {{ $currentLoggedInPage * $perPageLoggedIn >= $totalLoggedInTodayUsers ? 'disabled' : '' }}>
                                            Next <i class="bi bi-chevron-right"></i>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

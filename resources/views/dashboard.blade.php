@extends('layouts.logapp')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Total Folders Card -->
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card info-card sales-card shadow-sm hover-effect">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Total Folders</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
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
                        <h5 class="card-title text-success">Total Subfolders</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white"
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
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white"
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
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white"
                                        data-bs-toggle="tooltip" title="Online Users">
                                        <i class="bi bi-person-check"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 class="fs-5">Online Users</h6>
                                        <h6 class="fs-4">{{ $totalOnlineUsers }}</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Offline Users -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-secondary text-white"
                                        data-bs-toggle="tooltip" title="Offline Users">
                                        <i class="bi bi-person-x"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 class="fs-5">Offline Users</h6>
                                        <h6 class="fs-4">{{ $totalOfflineUsers }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center">
                                <label for="per_page" class="me-2 mb-0">Show</label>
                                <select name="per_page" id="per_page" class="form-select d-inline w-auto me-2"
                                    onchange="this.form.submit()">
                                    @for ($i = 5; $i <= 50; $i += 5)
                                        <option value="{{ $i }}" {{ $perPage == $i ? 'selected' : '' }}>{{ $i }}</option>
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
                                @foreach ($onlineUsers as $index => $user)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>{{ $user->username }}</td>
                                        <td><span class="badge bg-success">Online</span></td>
                                    </tr>
                                @endforeach
                                @foreach ($offlineUsers as $index => $user)
                                    <tr>
                                        <th scope="row">{{ $index + 1 + $onlineUsers->count() }}</th>
                                        <td>{{ $user->username }}</td>
                                        <td><span class="badge bg-secondary">Offline</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Logged In Today Card -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card recent-sales overflow-auto shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Users Logged In Today</h5>

                        <table class="table table-borderless datatable mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Last Login</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($loggedInTodayUsers as $index => $user)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->last_login_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

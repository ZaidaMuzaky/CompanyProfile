@extends('layouts.logapp')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Total Folders Card -->
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card info-card sales-card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Total Folders</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white">
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
                <div class="card info-card sales-card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-success">Total Subfolders</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white">
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
                <div class="card info-card sales-card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-info">Total Files</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white">
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
                        <h5 class="card-title">User Status <span>| Sekarang</span></h5>

                        <div class="d-flex align-items-center mb-3">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-secondary text-white">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="fs-4">{{ $totalOnlineUsers }} Online</h6>
                                <h6 class="fs-4">{{ $totalOfflineUsers }} Offline</h6>
                            </div>
                        </div>

                        <div class="mt-3">
                            <form method="GET" action="{{ route('dashboard') }}">
                                <label for="per_page">Show</label>
                                <select name="per_page" id="per_page" class="form-select d-inline w-auto"
                                    onchange="this.form.submit()">
                                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                </select>
                                <label for="per_page">entries</label>
                            </form>
                        </div>

                        <table class="table table-borderless datatable mt-3">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Status</h>
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
                                        <th scope="row">{{ $index + 1 + count($onlineUsers) }}</th>
                                        <td>{{ $user->username }}</td>
                                        <td><span class="badge bg-secondary">Offline</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center">
                            {{ $onlineUsers->appends(['offline_page' => $offlineUsers->currentPage(), 'per_page' => $perPage])->links() }}
                            {{ $offlineUsers->appends(['online_page' => $onlineUsers->currentPage(), 'per_page' => $perPage])->links() }}
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
                        <h5 class="card-title">Users Logged In Today</h5>

                        <table class="table table-borderless datatable mt-3">
                            <thead>
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

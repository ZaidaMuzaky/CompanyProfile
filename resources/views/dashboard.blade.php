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
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="fs-4">{{ $totalFolders }}</h6>
                                <span class="text-muted small pt-2 ps-1">Total number of Section</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Subfolders Card -->
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card info-card sales-card shadow-sm hover-effect">
                    <div class="card-body">
                        <h5 class="card-title text-success">Total Model</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white"
                                data-bs-toggle="tooltip" title="Total Subfolders">
                                <i class="fas fa-folder"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="fs-4">{{ $totalSubfolders }}</h6>
                                <span class="text-muted small pt-2 ps-1">Total number of Model</span>
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
                                <i class="fas fa-file-alt"></i>
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

{{--         
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h4 class="mb-3 pt-4 text-center fw-bold">Grafik Data Inspection</h4>
                <form method="GET" class="mb-4 text-center">
                    <label for="range" class="me-2 fw-semibold">Tampilkan data berdasarkan:</label>
                    <select name="range" id="range" onchange="this.form.submit()" class="form-select w-auto d-inline-block">
                        <option value="all" {{ $range == 'all' ? 'selected' : '' }}>Semua Data</option>
                        <option value="7" {{ $range == '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30" {{ $range == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                    </select>
                </form>
                <div class="row d-flex gx-4"> <!-- Flexbox with horizontal gap -->
                    <!-- Status Chart (Left Edge) -->
                    <div class="col-md-4 mb-4 d-flex justify-content-start">
                        <div class="card border-0 shadow-sm w-100">
                            <div class="card-body d-flex flex-column align-items-center">
                                <h6 class="card-title text-center fw-semibold">Status</h6>
                                <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                                    <canvas id="statusChart"></canvas>
                                </div>
                                <div class="chart-data-summary mt-3">
                                    @php
                                        $totalStatus = array_sum($statusCount);
                                    @endphp
                                    <div class="data-item">
                                        <span class="color-box" style="background-color: #4CAF50;"></span>
                                        <span class="label">Approved</span>
                                        <span class="value">{{ $statusCount['Approved'] }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="color-box" style="background-color: #FFC107;"></span>
                                        <span class="label">Pending</span>
                                        <span class="value">{{ $statusCount['Pending'] }} </span>
                                    </div>
                                    <div class="data-item">
                                        <span class="color-box" style="background-color: #F44336;"></span>
                                        <span class="label">Rejected</span>
                                        <span class="value">{{ $statusCount['Rejected'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Status Case Chart (Centered) -->
                    <div class="col-md-4 mb-4 d-flex justify-content-center">
                        <div class="card border-0 shadow-sm w-100">
                            <div class="card-body d-flex flex-column align-items-center">
                                <h6 class="card-title text-center fw-semibold">Status Case</h6>
                                <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                                    <canvas id="caseChart"></canvas>
                                </div>
                                <div class="chart-data-summary mt-3">
                                    @php
                                        $totalCase = array_sum($statusCaseCount);
                                    @endphp
                                    <div class="data-item">
                                        <span class="color-box" style="background-color: #FF9800;"></span>
                                        <span class="label">Open</span>
                                        <span class="value">{{ $statusCaseCount['open'] }} </span>
                                    </div>
                                    <div class="data-item">
                                        <span class="color-box" style="background-color: #2196F3;"></span>
                                        <span class="label">Close</span>
                                        <span class="value">{{ $statusCaseCount['close'] }} </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Action Type Chart (Right Edge) -->
                    <div class="col-md-4 mb-4 d-flex justify-content-end">
                        <div class="card border-0 shadow-sm w-100">
                            <div class="card-body d-flex flex-column align-items-center">
                                <h6 class="card-title text-center fw-semibold">Action Type</h6>
                                <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                                    <canvas id="actionChart"></canvas>
                                </div>
                                <div class="chart-data-summary mt-3">
                                    @php
                                        $totalAction = array_sum($actionCount);
                                    @endphp
                                    @foreach ($actionCount as $label => $value)
                                        <div class="data-item">
                                            <span class="color-box" style="background-color: {{ $loop->index == 0 ? '#3F51B5' : ($loop->index == 1 ? '#4CAF50' : ($loop->index == 2 ? '#FF9800' : ($loop->index == 3 ? '#9C27B0' : '#F44336'))) }};"></span>
                                            <span class="label">{{ $label }}</span>
                                            <span class="value">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        
        <style>
            .card {
                height: auto; /* Adjust to content */
            }
            .card-body {
                display: flex;
                flex-direction: column;
            }
            .chart-container {
                position: relative;
                width: 100%; /* Full width of column */
                height: 300px;
                margin: 0 auto; /* Center chart within column */
            }
            canvas {
                max-width: 100% !important;
                max-height: 300px !important;
            }
            .chart-data-summary {
                width: 100%; /* Full width of column */
                margin-top: 1rem;
                font-size: 15px;
                font-family: 'Arial', sans-serif;
            }
            .data-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 8px 12px;
                margin-bottom: 6px;
                background-color: #f8f9fa;
                border-radius: 4px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }
            .color-box {
                display: inline-block;
                width: 16px;
                height: 16px;
                margin-right: 10px;
                border: 1px solid #ccc;
                border-radius: 3px;
            }
            .label {
                flex: 1;
                font-weight: 500;
                color: #333;
            }
            .value {
                font-weight: 600;
                color: #555;
            }
            @media (max-width: 768px) {
                .row {
                    flex-direction: column; /* Stack columns vertically */
                }
                .col-md-4 {
                    justify-content: center !important; /* Center all charts on mobile */
                }
                .chart-container {
                    height: 200px;
                }
                canvas {
                    max-height: 200px !important;
                }
                .chart-data-summary {
                    font-size: 13px;
                }
                .data-item {
                    padding: 6px 10px;
                }
                .color-box {
                    width: 14px;
                    height: 14px;
                }
            }
        </style>
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new Chart(document.getElementById('statusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Approved', 'Pending', 'Rejected'],
                        datasets: [{
                            data: [{{ $statusCount['Approved'] }}, {{ $statusCount['Pending'] }}, {{ $statusCount['Rejected'] }}],
                            backgroundColor: ['#4CAF50', '#FFC107', '#F44336']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: { display: false },
                            legend: { display: false }
                        }
                    }
                });
        
                new Chart(document.getElementById('caseChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Open', 'Close'],
                        datasets: [{
                            data: [{{ $statusCaseCount['open'] }}, {{ $statusCaseCount['close'] }}],
                            backgroundColor: ['#FF9800', '#2196F3']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: { display: false },
                            legend: { display: false }
                        }
                    }
                });
        
                new Chart(document.getElementById('actionChart'), {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode(array_keys($actionCount)) !!},
                        datasets: [{
                            data: {!! json_encode(array_values($actionCount)) !!},
                            backgroundColor: ['#3F51B5', '#4CAF50', '#FF9800', '#9C27B0', '#F44336']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: { display: false },
                            legend: { display: false }
                        }
                    }
                });
            });
        </script>
        
        <!-- Login Recap Card -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card recent-sales overflow-auto shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Rekap Login <span>| Minggu, Bulan, Tahun</span></h5>

                        <div class="row mb-3">
                            <!-- Weekly Logins -->
                            <div class="col-md-4 d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white me-3">
                                    <i class="fas fa-calendar-week"></i>
                                </div>
                                <div>
                                    <h6 class="fs-5">Login Minggu Ini</h6>
                                    <h6 class="fs-4">{{ $weeklyLogins }}</h6>
                                </div>
                            </div>
                            <!-- Monthly Logins -->
                            <div class="col-md-4 d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white me-3">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <h6 class="fs-5">Login Bulan Ini</h6>
                                    <h6 class="fs-4">{{ $monthlyLogins }}</h6>
                                </div>
                            </div>
                            <!-- Yearly Logins -->
                            <div class="col-md-4 d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white me-3">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div>
                                    <h6 class="fs-5">Login Tahun Ini</h6>
                                    <h6 class="fs-4">{{ $yearlyLogins }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visitor Recap Card -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card recent-sales overflow-auto shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Rekap Pengunjung <span>| Minggu, Bulan, Tahun</span></h5>

                        <div class="row mb-3">
                            <!-- Weekly Visitors -->
                            <div class="col-md-4 d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning text-white me-3">
                                    <i class="fas fa-user-clock"></i>
                                </div>
                                <div>
                                    <h6 class="fs-5">Pengunjung Minggu Ini</h6>
                                    <h6 class="fs-4">{{ $weeklyVisitors }}</h6>
                                </div>
                            </div>
                            <!-- Monthly Visitors -->
                            <div class="col-md-4 d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger text-white me-3">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                                <div>
                                    <h6 class="fs-5">Pengunjung Bulan Ini</h6>
                                    <h6 class="fs-4">{{ $monthlyVisitors }}</h6>
                                </div>
                            </div>
                            <!-- Yearly Visitors -->
                            <div class="col-md-4 d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-secondary text-white me-3">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <h6 class="fs-5">Pengunjung Tahun Ini</h6>
                                    <h6 class="fs-4">{{ $yearlyVisitors }}</h6>
                                </div>
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

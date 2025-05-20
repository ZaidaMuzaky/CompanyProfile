```blade
@extends('layouts.logapp')

@section('title', 'Form Approval')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Admin Backlog</a></li>
    <li class="breadcrumb-item active" aria-current="page">Form Approval</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold text-dark">Approval Backlog Form</h2>
                <p class="text-muted mb-0">Kelola persetujuan formulir backlog dari berbagai supervisor.</p>
                <hr class="mt-3 mb-0">
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <!-- Notifikasi -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Supervisor Tabs -->
                        <ul class="nav nav-tabs mb-4 border-0">
                            @foreach ($supervisorForms as $supervisor => $forms)
                                <li class="nav-item">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }} rounded-3 px-4 py-2"
                                        data-bs-toggle="tab" data-bs-target="#{{ Str::slug($supervisor) }}">
                                        {{ $supervisor }}
                                        <span class="badge bg-secondary ms-2 rounded-pill">{{ count($forms) }}</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mb-3">
                            <input type="text" id="searchCnUnit" class="form-control rounded-pill px-4 py-2"
                                placeholder="Cari CN Unit...">
                        </div>
                        <!-- Tab Content -->
                        <div class="tab-content">
                            @foreach ($supervisorForms as $supervisor => $forms)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                    id="{{ Str::slug($supervisor) }}">
                                    @if (count($forms) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <div class="row mb-4">
                                                </div>

                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="ps-4 py-3 text-uppercase small fw-semibold">Nama Mekanik
                                                        </th>
                                                        <th class="py-3 text-uppercase small fw-semibold">Section</th>
                                                        <th class="py-3 text-uppercase small fw-semibold">Supervisor</th>
                                                        <th class="py-3 text-uppercase small fw-semibold">Model Unit</th>
                                                        <th class="py-3 text-uppercase small fw-semibold">CN Unit</th>
                                                        <!-- Kolom CN Unit -->
                                                        <th class="py-3 text-uppercase small fw-semibold">Status</th>
                                                        <th class="py-3 text-uppercase small fw-semibold">Status Case</th>
                                                        <th class="py-3 text-uppercase small fw-semibold text-center">Aksi
                                                            Formulir
                                                        </th>
                                                        <th class="py-3 text-uppercase small fw-semibold text-center">Aksi
                                                            Case
                                                        </th>
                                                        <th class="py-3 text-uppercase small fw-semibold text-center">Action Inspection
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($forms as $form)
                                                        <tr class="border-top">
                                                            <td class="ps-4 py-3">{{ $form['Nama Mekanik'] ?? '-' }}</td>
                                                            <td class="py-3">{{ $form['Section'] ?? '-' }}</td>
                                                            <td class="py-3">{{ $form['Supervisor'] ?? '-' }}</td>
                                                            <td class="py-3">{{ $form['Model Unit'] ?? '-' }}
                                                            </td>
                                                            <td class="py-3 cn-unit">{{ $form['CN Unit'] ?? '-' }}</td>
                                                            <!-- Menampilkan CN Unit -->
                                                            <td class="py-3">
                                                                <span
                                                                    class="badge bg-{{ $form['Status'] == 'Approved' ? 'success' : ($form['Status'] == 'Rejected' ? 'danger' : 'warning text-dark') }} rounded-pill px-3 py-2">
                                                                    {{ $form['Status'] ?? 'Pending' }}
                                                                </span>
                                                            </td>
                                                            <td class="py-3">
                                                                <span
                                                                    class="badge bg-{{ $form['Status Case'] == 'Open' ? 'primary' : ($form['Status Case'] == 'Close' ? 'secondary-subtle text-dark' : 'secondary-subtle text-dark') }} rounded-pill px-3 py-2">
                                                                    {{ $form['Status Case'] }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center pe-4 py-3 text-nowrap">
                                                                <button
                                                                    class="btn btn-sm btn-outline-primary rounded-pill me-1"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#detailModal{{ $form['ID'] }}"
                                                                    data-bs-toggle="tooltip" title="Lihat detail formulir">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>

                                                                @if ($form['Status'] != 'Approved' && $form['Status'] != 'Rejected')
                                                                    <button
                                                                        class="btn btn-sm btn-outline-success rounded-pill me-1"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#approveModal{{ $form['ID'] }}"
                                                                        data-bs-toggle="tooltip" title="Setujui formulir">
                                                                        <i class="fas fa-check"></i>
                                                                    </button>
                                                                    <button
                                                                        class="btn btn-sm btn-outline-danger rounded-pill"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#rejectModal{{ $form['ID'] }}"
                                                                        data-bs-toggle="tooltip" title="Tolak formulir">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                @endif
                                                            </td>
                                                            <td class="text-center pe-4 py-3 text-nowrap">
                                                                <button class="btn btn-sm btn-outline-warning rounded-pill"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#caseModal{{ $form['ID'] }}"
                                                                    data-bs-toggle="tooltip" title="Update Status Case">
                                                                    <i class="fas fa-tools"></i>
                                                                </button>
                                                            </td>
                                                            <td class="py-3 text-nowrap text-center">
                                                                <button type="button" class="btn btn-sm btn-outline-primary fw-semibold"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#inspectionModal{{ $form['ID'] }}">
                                                                    <i class="bi bi-gear-fill me-1"></i>
                                                                </button>
                                                            
                                                               
                                                            </td>
                                                            
                                                            


                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    @else
                                        <div class="card-body">
                                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                            <h4 class="fw-bold mb-3">Belum Ada Formulir</h4>
                                            <p class="text-muted mb-0">Tidak ada formulir untuk supervisor
                                                {{ $supervisor }}.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @foreach ($supervisorForms as $supervisor => $forms)
        @foreach ($forms as $form)
            <!-- Detail Modal -->
            <div class="modal fade" id="detailModal{{ $form['ID'] }}" tabindex="-1"
                aria-labelledby="detailModalLabel{{ $form['ID'] }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg rounded-3">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title fw-semibold" id="detailModalLabel{{ $form['ID'] }}">
                                Detail Formulir #{{ $form['ID'] }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="row">
                                @foreach ($form as $key => $value)
                                    <div class="col-md-6 mb-4">
                                        <p class="mb-1 text-muted small fw-semibold">{{ $key }}</p>
                                        <p class="mb-0">
                                            @php
                                                // Pisahkan berdasarkan spasi, koma, atau newline
                                                $links = is_string($value)
                                                    ? preg_split('/[\s,]+/', $value, -1, PREG_SPLIT_NO_EMPTY)
                                                    : [];
                                                $isAllLinks = collect($links)->every(function ($link) {
                                                    return filter_var($link, FILTER_VALIDATE_URL);
                                                });
                                            @endphp

                                            @if ($isAllLinks && count($links) > 0)
                                                @foreach ($links as $i => $link)
                                                    <div class="mb-1">
                                                        <a href="{{ $link }}" target="_blank"
                                                            class="text-primary text-decoration-none fw-semibold">
                                                            <i class="fas fa-link me-1"></i> Lihat Dokumen
                                                            {{ count($links) > 1 ? $i + 1 : '' }}
                                                        </a>
                                                    </div>
                                                @endforeach
                                            @else
                                                {{ $value ?: '-' }}
                                            @endif
                                        </p>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal{{ $form['ID'] }}" tabindex="-1"
                aria-labelledby="rejectModalLabel{{ $form['ID'] }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-3">
                        <form method="POST" action="{{ route('admin.approvals.approve', $form['ID']) }}">
                            @csrf
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title fw-semibold" id="rejectModalLabel{{ $form['ID'] }}">
                                    Tolak Formulir #{{ $form['ID'] }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label for="note" class="form-label fw-semibold">Alasan Penolakan</label>
                                    <textarea name="note" id="note" class="form-control rounded-3" rows="4" required></textarea>
                                </div>
                                <input type="hidden" name="status" value="Rejected">
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                    data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-danger rounded-pill px-4">
                                    <i class="fas fa-times me-1"></i> Konfirmasi Tolak
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- acc modal --}}
            <div class="modal fade" id="approveModal{{ $form['ID'] }}" tabindex="-1"
                aria-labelledby="approveModalLabel{{ $form['ID'] }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-3">
                        <form method="POST" action="{{ route('admin.approvals.approve', $form['ID']) }}">
                            @csrf
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title fw-semibold" id="approveModalLabel{{ $form['ID'] }}">
                                    Setujui Formulir #{{ $form['ID'] }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <p class="mb-0">Apakah Anda yakin ingin menyetujui formulir ini?</p>
                                <input type="hidden" name="status" value="Approved">
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                    data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-success rounded-pill px-4">
                                    <i class="fas fa-check me-1"></i> Konfirmasi Setujui
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal Update Status Case & Note -->
            <div class="modal fade" id="caseModal{{ $form['ID'] }}" tabindex="-1"
                aria-labelledby="caseModalLabel{{ $form['ID'] }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-3">
                        <form method="POST" action="{{ route('admin.approvals.updateCase', $form['ID']) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title fw-semibold" id="caseModalLabel{{ $form['ID'] }}">
                                    Update Status Case #{{ $form['ID'] }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label for="status_case" class="form-label fw-semibold">Status Case</label>
                                    <select name="status_case" id="status_case" class="form-select rounded-3" required>
                                        <option value="Open" {{ $form['Status Case'] == 'Open' ? 'selected' : '' }}>Open
                                        </option>
                                        <option value="Close" {{ $form['Status Case'] == 'Close' ? 'selected' : '' }}>
                                            Close</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="note_case" class="form-label fw-semibold">Note Case</label>
                                    <textarea name="note_case" id="note_case" class="form-control rounded-3" rows="4">{{ $form['Note Case'] ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                    data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-warning rounded-pill px-4">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
    {{-- aksi Inspection --}}
    <div class="modal fade" id="inspectionModal{{ $form['ID'] }}" tabindex="-1"
    aria-labelledby="inspectionModalLabel{{ $form['ID'] }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        
        <form action="{{ route('update.action.inspection', ['id' => $form['ID']]) }}" method="POST">
            @csrf
            <div class="modal-content shadow-sm border-0 rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title fw-bold" id="inspectionModalLabel{{ $form['ID'] }}">
                        Edit Action Inspection
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    @php
                    $inspectionData = collect($form)->filter(function ($val, $key) {
                        return strpos($key, 'Inspection Description') !== false;
                    });
                @endphp
                
                @foreach ($inspectionData as $key => $value)
                    <div class="mb-3 d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1 pe-3 fw-semibold text-truncate" title="{{ $value }}">
                            {{ $value }}
                        </div>
                        <div style="min-width: 140px;">
                            @php
                            preg_match('/\[(.*?)\]/', $value, $match);
                            $selectedAction = strtoupper(trim(old("actions.$key") ?? ($match[1] ?? '')));
                        @endphp
                        
                    
                        <select name="actions[{{ $key }}]" class="form-select form-select-sm" required>
                            @foreach(['CHECK', 'INSTALL', 'REPLACE', 'MONITORING', 'REPAIR'] as $option)
                                <option value="{{ $option }}" {{ $selectedAction === $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                @endforeach
                
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        <i class="bi bi-save2 me-1"></i> Simpan Semua
                    </button>
                    <button type="button" class="btn btn-outline-secondary fw-semibold px-4"
                        data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchInput = document.getElementById('searchCnUnit');
            var tableRows = document.querySelectorAll('table tbody tr');

            searchInput.addEventListener('keyup', function() {
                var filter = searchInput.value.toUpperCase();
                tableRows.forEach(function(row) {
                    var cnUnitCell = row.cells[4]; // kolom ke-5 (0-indexed)
                    if (cnUnitCell) {
                        var cnText = cnUnitCell.textContent || cnUnitCell.innerText;
                        row.style.display = cnText.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
                    }
                });
            });
        });
    </script>
@endsection

@push('styles')
    <style>
        /* General Styling */
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .badge {
            font-size: 0.85rem;
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.8rem;
        }

        .modal-content {
            border-radius: 12px;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
        }

        .nav-tabs .nav-link {
            color: #495057;
            transition: background-color 0.2s ease;
        }

        .nav-tabs .nav-link:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .nav-tabs .nav-link.active {
            background-color: #ffffff;
            border-color: #dee2e6 #dee2e6 #ffffff;
            font-weight: 600;
        }

        .form-control {
            border-radius: 8px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.9rem;
            }

            .btn-sm {
                padding: 0.2rem 0.5rem;
                font-size: 0.75rem;
            }

            .modal-dialog {
                margin: 0.5rem;
            }

            .nav-tabs .nav-link {
                font-size: 0.9rem;
                padding: 0.5rem;
            }
        }
    </style>
@endpush




@push('scripts')
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // Approve form function
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    @endpush

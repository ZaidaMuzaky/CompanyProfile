@extends('layouts.logapp')

@section('title', 'Status Formulir Backlog')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Backlog</a></li>
    <li class="breadcrumb-item active" aria-current="page">Status Formulir Saya</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold text-dark">Status Formulir Saya</h2>
                <p class="text-muted mb-0">Lihat status pengajuan formulir backlog Anda di bawah ini.</p>
                <hr class="mt-3 mb-0">
            </div>
        </div>
        <div class="mb-3">
            <input type="text" id="searchCnUnit" class="form-control rounded-pill px-4 py-2"
                placeholder="Cari CN Unit...">
        </div>

        @if (count($userForms) > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr class="table-row">
                                            {{-- <th class="ps-4 py-2 text-uppercase small fw-semibold">Timestamp</th> --}}
                                            <th class="py-2 text-uppercase small fw-semibold">Tanggal Service</th>
                                            <th class="py-2 text-uppercase small fw-semibold">Model Unit</th>
                                            <th class="py-2 text-uppercase small fw-semibold">CN Unit</th>
                                            <th class="py-2 text-uppercase small fw-semibold">Status</th>
                                            <th class="py-2 text-uppercase small fw-semibold">Status Case</th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Aksi</th>
                                            <th class="py-3 text-uppercase small fw-semibold text-center">Aksi
                                                Case
                                            </th>
                                            <th class="py-3 text-uppercase small fw-semibold text-center">Action
                                                Inspection
                                            </th>
                                    </thead>
                                    <tbody>
                                        @php
                                            $rejectedForms = collect($userForms)->filter(function ($form) {
                                                return $form['Status'] === 'Rejected';
                                            });

                                            $otherForms = collect($userForms)->filter(function ($form) {
                                                return $form['Status'] !== 'Rejected';
                                            });

                                            $sortedForms = $rejectedForms->concat($otherForms);
                                        @endphp
                                        @foreach ($sortedForms as $index => $form)
                                            <tr class="border-top table-row">
                                                {{-- <td class="ps-4 py-2 table-cell">{{ $form['Timestamp'] ?? '-' }}</td> --}}
                                                <td class="py-2 table-cell">{{ $form['Tanggal Service'] ?? '-' }}</td>
                                                <td class="py-2 table-cell fw-semibold">{{ $form['Model Unit'] ?? '-' }}
                                                </td>
                                                <td class="py-2 table-cell">{{ $form['CN Unit'] ?? '-' }}</td>
                                                <td class="py-2 table-cell">
                                                    <span
                                                        class="badge bg-{{ $form['Status'] == 'Rejected' ? 'danger' : ($form['Status'] == 'Pending' ? 'warning text-dark' : 'success') }} rounded-pill px-3 py-2">
                                                        {{ $form['Status'] }}
                                                    </span>
                                                </td>
                                                <td class="py-2 table-cell">
                                                    <span
                                                        class="badge bg-{{ $form['Status Case'] == 'Open' ? 'primary' : ($form['Status Case'] == 'Close' ? 'secondary-subtle text-dark' : 'secondary-subtle text-dark') }} rounded-pill px-3 py-2">
                                                        {{ $form['Status Case'] }}
                                                    </span>
                                                </td>
                                                {{-- <td class="py-2 table-cell">{{ $form['Status Case'] ?? '-' }}</td> --}}
                                                <td class="pe-4 py-2 action-buttons text-center">
                                                    <button class="btn btn-sm btn-outline-primary rounded-pill mb-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $index }}"
                                                        data-bs-toggle="tooltip" title="Lihat detail formulir">
                                                        <i class="fas fa-eye me-1"></i>
                                                    </button>

                                                    @if (($form['Status'] ?? '') === 'Rejected')
                                                        <a href="{{ route('user.backlog.edit', $form['ID']) }}"
                                                            class="btn btn-warning btn-sm rounded-pill mb-1"
                                                            data-bs-toggle="tooltip" title="Edit formulir">
                                                            <i class="fas fa-edit me-1"></i>
                                                        </a>
                                                        <form action="{{ route('user.backlog.destroy', $form['ID']) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Yakin ingin menghapus formulir ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm rounded-pill mb-1"
                                                                data-bs-toggle="tooltip" title="Hapus formulir">
                                                                <i class="fas fa-trash-alt me-1"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if ($form['Status'] === 'Pending')
                                                        <form action="{{ route('user.backlog.destroy', $form['ID']) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Yakin ingin menghapus formulir ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm rounded-pill mb-1"
                                                                data-bs-toggle="tooltip" title="Hapus formulir">
                                                                <i class="fas fa-trash-alt me-1"></i></button>
                                                        </form>
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
                                                {{-- TOMBOL DALAM TABEL --}}
                                                <td class="py-3 text-nowrap text-center">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary fw-semibold"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#inspectionModal{{ $form['ID'] }}">
                                                        <i class="bi bi-gear-fill me-1"></i>
                                                    </button>
                                                </td>
                                                {{-- aksi Inspection --}}
                                                <div class="modal fade" id="inspectionModal{{ $form['ID'] }}"
                                                    tabindex="-1"
                                                    aria-labelledby="inspectionModalLabel{{ $form['ID'] }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">

                                                        <form
                                                            action="{{ route('update.action.inspection', ['id' => $form['ID']]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-content shadow-sm border-0 rounded-4">
                                                                <div
                                                                    class="modal-header bg-primary text-white rounded-top-4">
                                                                    <h5 class="modal-title fw-bold"
                                                                        id="inspectionModalLabel{{ $form['ID'] }}">
                                                                        Edit Action Inspection
                                                                    </h5>
                                                                    <button type="button" class="btn-close btn-close-white"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">

                                                                    @php
                                                                        $inspectionData = collect($form)->filter(
                                                                            function ($val, $key) {
                                                                                return strpos(
                                                                                    $key,
                                                                                    'Inspection Description',
                                                                                ) !== false && !empty($val);
                                                                            },
                                                                        );
                                                                    @endphp

                                                                    @foreach ($inspectionData as $key => $value)
                                                                        <div
                                                                            class="mb-3 d-flex align-items-center justify-content-between">
                                                                            <div class="flex-grow-1 pe-3 fw-semibold text-truncate"
                                                                                title="{{ $value }}">
                                                                                {{ $value }}
                                                                            </div>
                                                                            <div style="min-width: 140px;">
                                                                                @php
                                                                                    preg_match(
                                                                                        '/\[(.*?)\]/',
                                                                                        $value,
                                                                                        $match,
                                                                                    );
                                                                                    $selectedAction = strtoupper(
                                                                                        trim(
                                                                                            old(
                                                                                                "actions.$key",
                                                                                                $match[1] ?? '',
                                                                                            ),
                                                                                        ),
                                                                                    );
                                                                                    $options = [
                                                                                        'CHECK',
                                                                                        'INSTALL',
                                                                                        'REPLACE',
                                                                                        'MONITORING',
                                                                                        'REPAIR',
                                                                                    ];
                                                                                @endphp



                                                                                <select
                                                                                    name="actions[{{ $key }}]"
                                                                                    class="form-select form-select-sm"
                                                                                    required>
                                                                                    @foreach ($options as $option)
                                                                                        <option
                                                                                            value="{{ $option }}"
                                                                                            {{ $selectedAction === $option ? 'selected' : '' }}>
                                                                                            {{ $option }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach



                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                        class="btn btn-primary fw-semibold px-4">
                                                                        <i class="bi bi-save2 me-1"></i> Simpan
                                                                        Semua
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-outline-secondary fw-semibold px-4"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Detail -->
            @foreach ($userForms as $index => $form)
                <div class="modal fade" id="detailModal{{ $index }}" tabindex="-1"
                    aria-labelledby="detailModalLabel{{ $index }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content border-0 shadow-lg rounded-3">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title fw-semibold" id="detailModalLabel{{ $index }}">
                                    Detail Formulir Backlog
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <p class="mb-1 text-muted small">Tanggal Service</p>
                                        <p class="mb-0 fw-bold">{{ $form['Tanggal Service'] ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <p class="mb-1 text-muted small">Status</p>
                                        <span
                                            class="badge bg-{{ $form['Status'] == 'Rejected' ? 'danger' : ($form['Status'] == 'Pending' ? 'warning text-dark' : 'success') }} rounded-pill px-3 py-2">
                                            {{ $form['Status'] }}
                                        </span>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <p class="mb-1 text-muted small">Status Case</p>
                                        <span
                                            class="badge bg-{{ $form['Status Case'] == 'Open' ? 'primary' : ($form['Status Case'] == 'Close' ? 'secondary-subtle text-dark' : 'secondary-subtle text-dark') }} rounded-pill px-3 py-2">
                                            {{ $form['Status Case'] }}
                                        </span>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle">
                                        <tbody>
                                            @foreach ($form as $key => $value)
                                                <tr>
                                                    <th class="w-40 bg-light p-3">{{ $key }}</th>
                                                    <td class="p-3">
                                                        @php
                                                            $links = preg_split('/[\s,]+/', $value);
                                                            $isAllLinks = collect($links)->every(function ($link) {
                                                                return filter_var($link, FILTER_VALIDATE_URL);
                                                            });
                                                        @endphp

                                                        @if ($isAllLinks && count($links) > 0)
                                                            @foreach ($links as $i => $link)
                                                                <div class="mb-1">
                                                                    <a href="{{ $link }}" target="_blank"
                                                                        class="text-primary text-decoration-none fw-semibold">
                                                                        <i class="fas fa-link me-1"></i> Buka Eviden
                                                                        {{ count($links) > 1 ? $i + 1 : '' }}
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
                                        <select name="status_case" id="status_case" class="form-select rounded-3"
                                            required>
                                            <option value="Open" {{ $form['Status Case'] == 'Open' ? 'selected' : '' }}>
                                                Open
                                            </option>
                                            <option value="Close"
                                                {{ $form['Status Case'] == 'Close' ? 'selected' : '' }}>
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
        @else
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3 text-center py-5">
                        <div class="card-body">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h4 class="fw-bold mb-3">Belum Ada Formulir</h4>
                            <p class="text-muted mb-0">Anda belum mengirimkan formulir backlog. Mulai dengan membuat
                                formulir
                                baru.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchInput = document.getElementById('searchCnUnit');
            var tableRows = document.querySelectorAll('table tbody tr');

            searchInput.addEventListener('keyup', function() {
                var filter = searchInput.value.toUpperCase();
                tableRows.forEach(function(row) {
                    var cnUnitCell = row.cells[3]; // CN Unit berada di kolom ke-4 (0-indexed = 3)
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
            line-height: 1;
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

        /* Table Row and Cell Styling */
        .table-row {
            display: flex;
            align-items: center;
            min-height: 48px;
            /* Ensure consistent row height */
        }

        .table-cell {
            display: flex;
            align-items: center;
            min-height: 48px;
            box-sizing: border-box;
        }

        /* Action Buttons Styling */
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 0.5rem;
            min-height: 48px;
            box-sizing: border-box;
            /* border: 1px solid red; */
            /* Debugging: Uncomment to check alignment */
        }

        .action-placeholder {
            min-width: 28px;
            text-align: center;
            line-height: 1;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.9rem;
            }

            .btn-sm {
                padding: 0.2rem 0.5rem;
                font-size: 0.75rem;
                width: 100%;
                text-align: center;
                line-height: 1;
            }

            .action-buttons {
                flex-direction: column;
                align-items: flex-end;
                gap: 0.5rem;
                justify-content: center;
            }

            .action-buttons .action-placeholder {
                width: 100%;
                text-align: right;
            }

            .modal-dialog {
                margin: 0.5rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Initialize tooltips for better UX
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush

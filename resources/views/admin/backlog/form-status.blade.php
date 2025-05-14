@extends('layouts.logapp')

@section('title', 'Semua Status Formulir')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Admin Backlog</a></li>
    <li class="breadcrumb-item active" aria-current="page">Semua Status Formulir</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold text-dark">Semua Status Formulir</h2>
                <p class="text-muted mb-0">Lihat status semua pengajuan formulir backlog di bawah ini.</p>
                <hr class="mt-3 mb-0">
            </div>
        </div>
        <div class="mb-3">
            <input type="text" id="searchCnUnit" class="form-control rounded-pill px-4 py-2"
                placeholder="Cari CN Unit...">
        </div>

        @if (count($allForms) > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4 py-2 text-uppercase small fw-semibold text-center">Username</th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Timestamp</th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Tanggal Service
                                            </th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Model Unit</th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">CN Unit</th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Status</th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (collect($allForms)->sortBy(function ($form) {
            return $form['Status'] === 'Rejected' ? 0 : 1;
        }) as $index => $form)
                                            <tr class="border-top">
                                                <td class="ps-4 py-2 text-center">{{ $form['Username'] ?? '-' }}</td>
                                                <td class="py-2 text-center">{{ $form['Timestamp'] ?? '-' }}</td>
                                                <td class="py-2 text-center">{{ $form['Tanggal Service'] ?? '-' }}</td>
                                                <td class="py-2 fw-semibold text-center">{{ $form['Model Unit'] ?? '-' }}
                                                </td>
                                                <td class="py-2 text-center">{{ $form['CN Unit'] ?? '-' }}</td>
                                                <td class="py-2 text-center">
                                                    <span
                                                        class="badge bg-{{ $form['Status'] == 'Rejected' ? 'danger' : ($form['Status'] == 'Pending' ? 'warning text-dark' : 'success') }} rounded-pill px-3 py-2">
                                                        {{ $form['Status'] }}
                                                    </span>
                                                </td>
                                                <td class="text-center pe-4 py-2">
                                                    <button class="btn btn-sm btn-outline-primary rounded-pill"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $index }}"
                                                        data-bs-toggle="tooltip" title="Lihat detail formulir">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($allForms as $index => $form)
                <div class="modal fade" id="detailModal{{ $index }}" tabindex="-1"
                    aria-labelledby="detailModalLabel{{ $index }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content border-0 shadow-lg rounded-3">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title fw-semibold" id="detailModalLabel{{ $index }}">
                                    Detail Formulir - {{ $form['Username'] ?? '-' }}
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
                                    <div class="col-md-6 mb-3">
                                        <p class="mb-1 text-muted small">Status</p>
                                        <span
                                            class="badge bg-{{ $form['Status'] == 'Rejected' ? 'danger' : ($form['Status'] == 'Pending' ? 'warning text-dark' : 'success') }} rounded-pill px-3 py-2">
                                            {{ $form['Status'] }}
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
            @endforeach
        @else
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3 text-center py-5">
                        <div class="card-body">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h4 class="fw-bold mb-3">Belum Ada Formulir</h4>
                            <p class="text-muted mb-0">Tidak ditemukan data formulir backlog dari siapapun.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
@endsection

@push('styles')
    <style>
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

        .table tbody td {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
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
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush

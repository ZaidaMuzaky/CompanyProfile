@extends('layouts.logapp')

@section('title', 'Status Formulir Inspeksi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Inspection After Service</a></li>
    <li class="breadcrumb-item active" aria-current="page">Status Semua Formulir Inspeksi</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold text-dark">Semua Status Formulir</h2>
                <p class="text-muted mb-0">Lihat status semua pengajuan formulir Inspeksi di bawah ini.</p>
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
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4 py-2 text-uppercase small fw-semibold text-center">Username</th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Tanggal Service
                                            </th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Model Unit</th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">CN Unit</th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Status</th>
                                            <th class="py-3 text-uppercase small fw-semibold">Temuan</th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (collect($userForms)->sortBy(function ($form) {
            return $form['Status'] === 'Rejected' ? 0 : 1;
        }) as $index => $form)
                                            <tr class="border-top">
                                                <td class="ps-4 py-2 text-center">{{ $form['Username'] ?? '-' }}</td>
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
                                                <td class="py-3">
                                                    @php
                                                        if (!function_exists('is_list_array')) {
                                                            function is_list_array($array) {
                                                                if (!is_array($array)) return false;
                                                                return array_keys($array) === range(0, count($array) - 1);
                                                            }
                                                        }
                                                
                                                        $temuanList = [];
                                                
                                                        foreach ($form as $header => $value) {
                                                            $decoded = json_decode($value, true);
                                                
                                                            if (!is_array($decoded)) continue;
                                                
                                                            if (is_list_array($decoded)) {
                                                                foreach ($decoded as $item) {
                                                                    if (($item['statusCase'] ?? '') === 'open') {
                                                                        $temuanList[] = [
                                                                            'temuan' => $item['temuan'] ?? $header,
                                                                            'action' => $item['action'] ?? '',
                                                                            'statusCase' => $item['statusCase'] ?? '',
                                                                        ];
                                                                    }
                                                                }
                                                            } elseif (($decoded['statusCase'] ?? '') === 'open') {
                                                                $temuanList[] = [
                                                                    'temuan' => $decoded['temuan'] ?? $header,
                                                                    'action' => $decoded['action'] ?? '',
                                                                    'statusCase' => $decoded['statusCase'] ?? '',
                                                                ];
                                                            }
                                                        }
                                                    @endphp
                                                
                                                    <style>
                                                        .badge-action-CHECK { background-color: #fff3cd; color: #000; }
                                                        .badge-action-INSTALL { background-color: #cce5ff; color: #000; }
                                                        .badge-action-REPLACE { background-color: #f8d7da; color: #000; }
                                                        .badge-action-MONITORING { background-color: #d1ecf1; color: #000; }
                                                        .badge-action-REPAIR { background-color: #d4edda; color: #000; }
                                                        .badge-status-open { background-color: #fff3cd; color: #000; }
                                                        .badge-status-close { background-color: #d4edda; color: #000; }
                                                    </style>
                                                
                                                    <div class="container-fluid">
                                                        @if (count($temuanList))
                                                            <ul class="list-group mb-0">
                                                                @foreach ($temuanList as $item)
                                                                    <li class="list-group-item border-0 border-bottom">
                                                                        <div class="mb-2">
                                                                            <strong class="text-dark">{{ $item['temuan'] }}</strong>
                                                                        </div>
                                                                        <div class="d-flex flex-column flex-sm-row gap-2">
                                                                            <span class="badge badge-action-{{ $item['action'] ?? 'UNKNOWN' }} px-2 py-1">
                                                                                {{ $item['action'] ?? '-' }}
                                                                            </span>
                                                                            <span class="badge badge-status-{{ $item['statusCase'] ?? 'open' }} px-2 py-1">
                                                                                {{ ucfirst($item['statusCase'] ?? '-') }}
                                                                            </span>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            <span class="text-muted fst-italic">Tidak ada temuan open.</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="text-center pe-4 py-2">
                                                    <button class="btn btn-sm btn-outline-primary rounded-pill"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $index }}"
                                                        data-bs-toggle="tooltip" title="Lihat detail formulir">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </button>
                                                    <!-- Tombol Hapus -->
                                                    <form
                                                        action="{{ route('admin.inspection.destroy', $form['ID']) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger rounded-pill"
                                                            data-bs-toggle="tooltip" title="Hapus formulir">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
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
{{-- detail modal --}}
            @foreach ($userForms as $index => $form)
            @php
            $lubricantKeys = [
                'Engine Oil level',
                'Radiator Coolant Level',
                'Final Drive Oil Level',
                'Differential Oil Level',
                'Transmission & Steering Oil Level',
                'Hydraulic Oil Level',
                'Fuel Level',
                'PTO Oil',
                'Brake Oil',
                'Compressor Oil Level',
            ];

            $afterRepairKeys = [
                'Check Leaking',
                'Check tighting Bolt',
                'Check Abnormal Noise',
                'Check Abnormal Temperature',
                'Check Abnormal Smoke/Smell',
                'Check Abnormal Vibration',
                'Check Abnormal Bending/Crack',
                'Check Abnormal Tention',
                'Check Abnormal Pressure',
                'Check Error Vault Code',
            ];

            $componentKeys = [
                'AC SYSTEM',
                'BRAKE SYSTEM',
                'DIFFERENTIAL & FINAL DRAVE',
                'ELECTRICAL SYSTEM',
                'ENGINE',
                'GENERAL ( ACCESSORIES, CABIN, ETC )',
                'HYDRAULIC SYSTEM',
                'IT SYSTEM',
                'MAIN FRAME / CHASSIS / VASSEL',
                'PERIODICAL SERVICE',
                'PNEUMATIC SYSTEM',
                'PREEICTIVE MAINTENANCE',
                'PREVENTIF MAINTENANCE',
                'PROBLEM SDT',
                'PROBLEM TYRE SDT',
                'STEERING SYSTEM',
                'TRANSMISSION SYSTEM',
                'TYRE',
                'UNDERRCARIAGE'
            ];

            // Gabungkan semua inspection keys supaya mudah pengecekan nanti
            $allInspectionKeys = array_merge($lubricantKeys, $afterRepairKeys, $componentKeys);
        @endphp

<div class="modal fade" id="detailModal{{ $index }}" tabindex="-1"
aria-labelledby="detailModalLabel{{ $index }}" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg rounded-3">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title fw-semibold" id="detailModalLabel{{ $index }}">
                Detail Formulir Inspeksi
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
                </div>
                {{-- Tampilkan data selain inspection keys, tanggal service, dan status --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <tbody>
                            @foreach ($form as $key => $value)
                                @if (!in_array($key, $allInspectionKeys) && $key != 'Tanggal Service' && $key != 'Status' && !in_array($key, $componentKeys))
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
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Bagian A. Check Level Lubricant Coolant --}}
                <h6 class="mb-3">A. Check Level Lubricant Coolant</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-striped align-middle">
                        <tbody>
                            @foreach ($lubricantKeys as $key)
                                @if (isset($form[$key]))
                                    <tr>
                                        <th class="w-40 bg-light p-3">{{ $key }}</th>
                                        <td class="p-3">
                                            @php
                                                $jsonData = json_decode($form[$key], true);
                                                $isJson = is_array($jsonData);
                                            @endphp
                                            @if ($isJson)
                                                <table class="table table-bordered mb-0" style="font-size: 0.9rem;">
                                                    <tbody>
                                                        @foreach ($jsonData as $jsonKey => $jsonValue)
                                                            <tr>
                                                                <th class="bg-light" style="width: 35%;">{{ ucfirst($jsonKey) }}</th>
                                                                <td>
                                                                    @if (strtolower($jsonKey) === 'statuscase')
                                                                        <span class="badge bg-{{ $jsonValue == 'open' ? 'danger' : 'success' }}">{{ ucfirst($jsonValue) }}</span>
                                                                    @elseif (strtolower($jsonKey) === 'action')
                                                                        <span class="badge bg-info text-dark">{{ $jsonValue }}</span>
                                                                    @elseif (strtolower($jsonKey) === 'evidence' && is_array($jsonValue))
                                                                        @foreach ($jsonValue as $i => $link)
                                                                            <div class="mb-1">
                                                                                <a href="{{ $link }}" target="_blank"
                                                                                    class="text-primary text-decoration-none fw-semibold">
                                                                                    <i class="fas fa-link me-1"></i> Buka Eviden {{ count($jsonValue) > 1 ? $i + 1 : '' }}
                                                                                </a>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        {{ $jsonValue }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                {{ $form[$key] }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>


                {{-- Bagian B. Check Job Condition After Repair --}}
                <h6 class="mb-3">B. Check Job Condition After Repair</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-striped align-middle">
                        <tbody>
                            @foreach ($afterRepairKeys as $key)
                                @if (isset($form[$key]))
                                    <tr>
                                        <th class="w-40 bg-light p-3">{{ $key }}</th>
                                        <td class="p-3">
                                            @php
                                                $jsonData = json_decode($form[$key], true);
                                                $isJson = is_array($jsonData);
                                            @endphp
                                            @if ($isJson)
                                                <table class="table table-bordered mb-0" style="font-size: 0.9rem;">
                                                    <tbody>
                                                        @foreach ($jsonData as $jsonKey => $jsonValue)
                                                            <tr>
                                                                <th class="bg-light" style="width: 35%;">{{ ucfirst($jsonKey) }}</th>
                                                                <td>
                                                                    @if (strtolower($jsonKey) === 'statuscase')
                                                                        <span class="badge bg-{{ $jsonValue == 'open' ? 'danger' : 'success' }}">{{ ucfirst($jsonValue) }}</span>
                                                                    @elseif (strtolower($jsonKey) === 'action')
                                                                        <span class="badge bg-info text-dark">{{ $jsonValue }}</span>
                                                                    @elseif (strtolower($jsonKey) === 'evidence' && is_array($jsonValue))
                                                                        @foreach ($jsonValue as $i => $link)
                                                                            <div class="mb-1">
                                                                                <a href="{{ $link }}" target="_blank"
                                                                                    class="text-primary text-decoration-none fw-semibold">
                                                                                    <i class="fas fa-link me-1"></i> Buka Eviden {{ count($jsonValue) > 1 ? $i + 1 : '' }}
                                                                                </a>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        {{ $jsonValue }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                {{ $form[$key] }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
{{-- Bagian C. Sub Component --}}
<h6 class="mb-3">C. Sub Component</h6>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-striped align-middle">
            <tbody>
            @foreach ($componentKeys as $key)
            @if (isset($form[$key]) && $form[$key] !== '')
                <tr>
                    <th class="w-40 bg-light p-3">{{ $key }}</th>
                    <td class="p-3">
                        @php
                            $jsonData = json_decode($form[$key], true);
                            $isJson = is_array($jsonData);
                        @endphp
                        @if ($isJson && count($jsonData) > 0)
                            @foreach ($jsonData as $temuan)
                                <table class="table table-bordered mb-3" style="font-size: 0.85rem;">
                                    <tbody>
                                        @foreach ($temuan as $label => $value)
                                            @continue(strtolower($label) === 'sub_component')
                                            <tr>
                                                <th class="bg-light" style="width: 30%;">{{ ucfirst($label) }}</th>
                                                <td>
                                                    @if (strtolower($label) === 'statuscase')
                                                        <span class="badge bg-{{ $value == 'open' ? 'danger' : 'success' }}">{{ ucfirst($value) }}</span>
                                                    @elseif (strtolower($label) === 'action')
                                                        <span class="badge bg-info text-dark">{{ $value }}</span>
                                                    @elseif (strtolower($label) === 'evidence' && is_array($value))
                                                        @foreach ($value as $i => $link)
                                                            <div class="mb-1">
                                                                <a href="{{ $link }}" target="_blank"
                                                                    class="text-primary text-decoration-none fw-semibold">
                                                                    <i class="fas fa-link me-1"></i> Buka Eviden {{ count($value) > 1 ? $i + 1 : '' }}
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
                            @endforeach
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            @endif
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
                <!-- Modal Update Status Case -->
                <div class="modal fade" id="caseModal{{ $form['ID'] }}" tabindex="-1"
                    aria-labelledby="caseModalLabel{{ $form['ID'] }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">

                        <form action="{{ route('user.inspection.updateCase', ['id' => $form['ID']]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content shadow-sm border-0 rounded-4">
                                <div class="modal-header bg-warning text-dark rounded-top-4">
                                    <h5 class="modal-title fw-bold" id="caseModalLabel{{ $form['ID'] }}">
                                        Edit Status Case - ID: {{ $form['ID'] }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    @php
                                        $caseData = collect($form)->filter(function ($val, $key) {
                                            return is_string($val) && strpos($val, '"statusCase"') !== false;
                                        });

                                        $groupedKeys = [
                                            'A. Check Level Lubricant Coolant' => [
                                                'Engine Oil level',
                                                'Radiator Coolant Level',
                                                'Final Drive Oil Level',
                                                'Differential Oil Level',
                                                'Transmission & Steering Oil Level',
                                                'Hydraulic Oil Level',
                                                'Fuel Level',
                                                'PTO Oil',
                                                'Brake Oil',
                                                'Compressor Oil Level',
                                            ],
                                            'B. Check Job Condition After Repair' => [
                                                'Check Leaking',
                                                'Check tighting Bolt',
                                                'Check Abnormal Noise',
                                                'Check Abnormal Temperature',
                                                'Check Abnormal Smoke/Smell',
                                                'Check Abnormal Vibration',
                                                'Check Abnormal Bending/Crack',
                                                'Check Abnormal Tention',
                                                'Check Abnormal Pressure',
                                                'Check Error Vault Code',
                                            ],
                                            'C. Sub Component' => [
                                                'AC SYSTEM',
                                                'BRAKE SYSTEM',
                                                'DIFFERENTIAL & FINAL DRAVE',
                                                'ELECTRICAL SYSTEM',
                                                'ENGINE',
                                                'GENERAL ( ACCESSORIES, CABIN, ETC )',
                                                'HYDRAULIC SYSTEM',
                                                'IT SYSTEM',
                                                'MAIN FRAME / CHASSIS / VASSEL',
                                                'PERIODICAL SERVICE',
                                                'PNEUMATIC SYSTEM',
                                                'PREEICTIVE MAINTENANCE',
                                                'PREVENTIF MAINTENANCE',
                                                'PROBLEM SDT',
                                                'PROBLEM TYRE SDT',
                                                'STEERING SYSTEM',
                                                'TRANSMISSION SYSTEM',
                                                'TYRE',
                                                'UNDERRCARIAGE'
                                            ],
                                        ];
                                    @endphp


                                    @foreach ($groupedKeys as $groupTitle => $keys)
                                        <h6 class="fw-bold mt-4">{{ $groupTitle }}</h6>
                                        <div class="row">
                                            @foreach ($keys as $key)
    @if (isset($caseData[$key]))
        @php
            $data = json_decode($caseData[$key], true);
            $status = $data['statusCase'] ?? 'open';
            $bgClass = $status === 'close' ? 'bg-danger text-white' : 'bg-primary text-white';
        @endphp
        <div class="col-md-3 mb-3">
            <label class="form-label fw-semibold text-truncate d-block" title="{{ $key }}">{{ $key }}</label>
            <input type="hidden" name="keys[]" value="{{ $key }}">
            <select name="statuses[{{ $key }}]" class="form-select status-select {{ $bgClass }}" required>
                <option value="open" {{ $status === 'open' ? 'selected' : '' }}>OPEN</option>
                <option value="close" {{ $status === 'close' ? 'selected' : '' }}>CLOSE</option>
            </select>
        </div>
    @endif
@endforeach

                                        </div>
                                    @endforeach
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            const selects = document.querySelectorAll('.status-select');
                                    
                                            selects.forEach(select => {
                                                select.addEventListener('change', function () {
                                                    // Hapus semua class warna dulu
                                                    this.classList.remove('bg-danger', 'bg-primary', 'text-white');
                                    
                                                    // Tambahkan class sesuai value terpilih
                                                    if (this.value === 'close') {
                                                        this.classList.add('bg-danger', 'text-white');
                                                    } else if (this.value === 'open') {
                                                        this.classList.add('bg-primary', 'text-white');
                                                    }
                                                });
                                            });
                                        });
                                    </script>
                                    
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-warning fw-semibold px-4">
                                        <i class="bi bi-save2 me-1"></i> Simpan
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary fw-semibold px-4"
                                        data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </form>
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
                            <p class="text-muted mb-0">Tidak ditemukan data formulir Inspeksi dari siapapun.</p>
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
                    var cnUnitCell = row.cells[3]; // kolom ke-5 (0-indexed)
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

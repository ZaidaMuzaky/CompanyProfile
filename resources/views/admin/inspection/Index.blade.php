@extends('layouts.logapp')

@section('title', 'Form Inspection Approval')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Inspection After Service</a></li>
    <li class="breadcrumb-item active" aria-current="page">Form Inspection Approval</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold text-dark">Approval Inspection Form</h2>
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
                                                        <th class="py-3 text-uppercase small fw-semibold">Model Unit</th>
                                                        <th class="py-3 text-uppercase small fw-semibold">CN Unit</th>
                                                        <!-- Kolom CN Unit -->
                                                        <th class="py-3 text-uppercase small fw-semibold">Status</th>
                                                        <th class="py-3 text-uppercase small fw-semibold">Temuan</th>
                                                        <th class="py-3 text-uppercase small fw-semibold text-center">Aksi
                                                            Formulir
                                                        </th>
                                                        <th class="py-3 text-uppercase small fw-semibold text-center">Aksi
                                                            Case
                                                        </th>
                                                        {{-- <th class="py-3 text-uppercase small fw-semibold text-center">Action
                                                            Inspection
                                                        </th> --}}

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($forms as $form)
                                                        <tr class="border-top">
                                                            <td class="ps-4 py-3">{{ $form['Nama Mekanik'] ?? '-' }}</td>
                                                            <td class="py-3">{{ $form['Section'] ?? '-' }}</td>
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
                                                                                        'key' => $header . '_' . ($item['temuan'] ?? uniqid()),
                                                                                    ];
                                                                                }
                                                                            }
                                                                        } elseif (($decoded['statusCase'] ?? '') === 'open') {
                                                                            $temuanList[] = [
                                                                                'temuan' => $decoded['temuan'] ?? $header,
                                                                                'action' => $decoded['action'] ?? '',
                                                                                'statusCase' => $decoded['statusCase'] ?? '',
                                                                                'key' => $header . '_' . ($decoded['temuan'] ?? uniqid()),
                                                                            ];
                                                                        }
                                                                    }
                                                                @endphp
                                                            
                                                                <style>
                                                                    .select-action-CHECK { background-color: #fff3cd; }     /* kuning */
                                                                    .select-action-INSTALL { background-color: #cce5ff; }   /* biru muda */
                                                                    .select-action-REPLACE { background-color: #f8d7da; }   /* merah muda */
                                                                    .select-action-MONITORING { background-color: #d1ecf1; }/* biru tosca */
                                                                    .select-action-REPAIR { background-color: #d4edda; }    /* hijau muda */
                                                                    .select-status-open { background-color: #fff3cd; }      /* kuning */
                                                                    .select-status-close { background-color: #d4edda; }     /* hijau muda */
                                                                </style>
                                                            
                                                                <div class="container-fluid">
                                                                    @if (count($temuanList))
                                                                        <ul class="list-group mb-0">
                                                                            @foreach ($temuanList as $index => $item)
                                                                                <li class="list-group-item border-0 border-bottom">
                                                                                    <div class="mb-2">
                                                                                        <strong class="text-dark">{{ $item['temuan'] }}</strong>
                                                                                    </div>
                                                                                    <div class="d-flex flex-column flex-sm-row gap-2">
                                                                                        <select class="form-select form-select-sm update-action"
                                                                                            data-type="action"
                                                                                            data-index="{{ $index }}"
                                                                                            data-key="{{ $item['key'] }}"
                                                                                            data-id="{{ $form['ID'] }}">
                                                                                            <option value="CHECK" {{ $item['action'] == 'CHECK' ? 'selected' : '' }}>CHECK</option>
                                                                                            <option value="INSTALL" {{ $item['action'] == 'INSTALL' ? 'selected' : '' }}>INSTALL</option>
                                                                                            <option value="REPLACE" {{ $item['action'] == 'REPLACE' ? 'selected' : '' }}>REPLACE</option>
                                                                                            <option value="MONITORING" {{ $item['action'] == 'MONITORING' ? 'selected' : '' }}>MONITORING</option>
                                                                                            <option value="REPAIR" {{ $item['action'] == 'REPAIR' ? 'selected' : '' }}>REPAIR</option>
                                                                                        </select>
                                                            
                                                                                        <select class="form-select form-select-sm update-action"
                                                                                            data-type="statusCase"
                                                                                            data-index="{{ $index }}"
                                                                                            data-key="{{ $item['key'] }}"
                                                                                            data-id="{{ $form['ID'] }}">
                                                                                            <option value="open" {{ $item['statusCase'] == 'open' ? 'selected' : '' }}>Open</option>
                                                                                            <option value="close" {{ $item['statusCase'] == 'close' ? 'selected' : '' }}>Close</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @else
                                                                        <span class="text-muted fst-italic">Tidak ada temuan open.</span>
                                                                    @endif
                                                                </div>
                                                            
                                                                <script>
                                                                    document.querySelectorAll('.update-action').forEach(function(select) {
                                                                        function updateSelectClass(el) {
                                                                            const type = el.dataset.type;
                                                                            const value = el.value;
                                                            
                                                                            // Reset semua warna dulu
                                                                            el.classList.remove(
                                                                                'select-action-CHECK',
                                                                                'select-action-INSTALL',
                                                                                'select-action-REPLACE',
                                                                                'select-action-MONITORING',
                                                                                'select-action-REPAIR',
                                                                                'select-status-open',
                                                                                'select-status-close'
                                                                            );
                                                            
                                                                            // Tambahkan class warna yang sesuai
                                                                            if (type === 'action') {
                                                                                el.classList.add('select-action-' + value);
                                                                            } else if (type === 'statusCase') {
                                                                                el.classList.add('select-status-' + value);
                                                                            }
                                                                        }
                                                            
                                                                        // Set warna awal saat load
                                                                        updateSelectClass(select);
                                                            
                                                                        select.addEventListener('change', function () {
                                                                            const value = this.value;
                                                                            const type = this.dataset.type;
                                                                            const key = this.dataset.key;
                                                                            const index = this.dataset.index;
                                                                            const id = this.dataset.id;
                                                            
                                                                            updateSelectClass(this); // update warna
                                                            
                                                                            fetch(`/update-temuan-case/${id}`, {
                                                                                method: 'POST',
                                                                                headers: {
                                                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                                                    'Content-Type': 'application/json'
                                                                                },
                                                                                body: JSON.stringify({
                                                                                    key: key,
                                                                                    index: index,
                                                                                    type: type,
                                                                                    value: value
                                                                                })
                                                                            })
                                                                            .then(response => response.json())
                                                                            .then(result => {
                                                                                if (!result.success) {
                                                                                    alert('Gagal memperbarui data.');
                                                                                }
                                                                            })
                                                                            .catch(error => {
                                                                                console.error('Error:', error);
                                                                                alert('Terjadi kesalahan saat menyimpan.');
                                                                            });
                                                                        });
                                                                    });
                                                                </script>
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
                'UNDERCARRIAGE',
            ];

            // Gabungkan semua inspection keys supaya mudah pengecekan nanti
            $allInspectionKeys = array_merge($lubricantKeys, $afterRepairKeys, $componentKeys);
        @endphp

        <div class="modal fade" id="detailModal{{ $form['ID'] }}" tabindex="-1"
            aria-labelledby="detailModalLabel{{ $form['ID'] }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg rounded-3">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-semibold" id="detailModalLabel{{ $form['ID'] }}">
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
                        // Decode JSON string jika ada
                        $jsonData = json_decode($form[$key], true);

                        // Cek apakah jsonData adalah array hasil decode JSON valid
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
                                                <span class="badge bg-{{ $jsonValue == 'open' ? 'danger' : 'success' }}">
                                                    {{ ucfirst($jsonValue) }}
                                                </span>
                                            @elseif (strtolower($jsonKey) === 'action')
                                                <span class="badge bg-info text-dark">{{ $jsonValue }}</span>
                                            @else
                                                {{ $jsonValue }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        @php
                            // Jika bukan JSON, cek apakah berupa link dan tampilkan link
                            $links = preg_split('/[\s,]+/', $form[$key]);
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
                            {{ $form[$key] }}
                        @endif
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
                                                <span class="badge bg-{{ $jsonValue == 'open' ? 'danger' : 'success' }}">
                                                    {{ ucfirst($jsonValue) }}
                                                </span>
                                            @elseif (strtolower($jsonKey) === 'action')
                                                <span class="badge bg-info text-dark">{{ $jsonValue }}</span>
                                            @else
                                                {{ $jsonValue }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        @php
                            $links = preg_split('/[\s,]+/', $form[$key]);
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
                            {{ $form[$key] }}
                        @endif
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
<div class="table-responsive mb-4" style="font-size: 0.875rem;">
<table class="table table-bordered table-striped align-middle mb-0">
<tbody>
    @foreach ($componentKeys as $key)
        @if (isset($form[$key]) && $form[$key] !== '')
            @php
                $jsonData = json_decode($form[$key], true);
                $isJson = is_array($jsonData);
            @endphp
            <tr>
                <th class="w-40 bg-light p-3">{{ $key }}</th>
                <td class="p-3">
                    @if ($isJson && count($jsonData) > 0)
                        @foreach ($jsonData as $temuan)
                            <table class="table table-bordered mb-3" style="font-size: 0.85rem;">
                                <tbody>
                                    @foreach ($temuan as $label => $value)
                                        <tr>
                                            <th class="bg-light" style="width: 30%;">
                                                {{ ucfirst($label) }}
                                            </th>
                                            <td>
                                                @if (strtolower($label) === 'statuscase')
                                                    <span class="badge bg-{{ $value == 'open' ? 'danger' : 'success' }}">
                                                        {{ ucfirst($value) }}
                                                    </span>
                                                @elseif (strtolower($label) === 'action')
                                                    <span class="badge bg-info text-dark">
                                                        {{ $value }}
                                                    </span>
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

            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal{{ $form['ID'] }}" tabindex="-1"
                aria-labelledby="rejectModalLabel{{ $form['ID'] }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-3">
                        <form method="POST" action="{{ route('admin.inspection.approve', $form['ID']) }}">
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
                        <form method="POST" action="{{ route('admin.inspection.approve', $form['ID']) }}">
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
                                        'UNDERGRADUATE',
                                        'WORK EQUIPMENT',
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
                                                // Check if data is an array of findings (Section C) or a single item (Sections A, B)
                                                $isSectionC = $groupTitle === 'C. Sub Component';
                                                $items = $isSectionC && is_array($data) && !isset($data['statusCase']) ? $data : [$data];
                                            @endphp
                                            @foreach ($items as $index => $item)
                                                @php
                                                    $status = $item['statusCase'] ?? 'open';
                                                    $bgClass = $status === 'close' ? 'bg-danger text-white' : 'bg-primary text-white';
                                                    // For Section C, create a unique name combining sub_component and temuan
                                                    $displayName = $isSectionC ? "{$key}-" . ($item['temuan'] ?? 'Unknown') : $key;
                                                    $inputName = $isSectionC ? "{$key}_{$index}" : $key;
                                                @endphp
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-semibold text-truncate d-block" title="{{ $displayName }}">{{ $displayName }}</label>
                                                    <input type="hidden" name="keys[]" value="{{ $inputName }}">
                                                    <select name="statuses[{{ $inputName }}]" class="form-select status-select {{ $bgClass }}" required>
                                                        <option value="open" {{ $status === 'open' ? 'selected' : '' }}>OPEN</option>
                                                        <option value="close" {{ $status === 'close' ? 'selected' : '' }}>CLOSE</option>
                                                    </select>
                                                </div>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
            
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const selects = document.querySelectorAll('.status-select');
                                    selects.forEach(select => {
                                        select.addEventListener('change', function () {
                                            this.classList.remove('bg-danger', 'bg-primary', 'text-white');
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
    @endforeach

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

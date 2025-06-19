@extends('layouts.logapp')

@section('title', 'Status Formulir Inspeksi Saya')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Inspection After Repair</a></li>
    <li class="breadcrumb-item active" aria-current="page">Status Formulir Inspeksi saya</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold text-dark">Status Formulir Inspeksi Saya</h2>
                <p class="text-muted mb-0">Lihat status pengajuan formulir Inspeksi Anda di bawah ini.</p>
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
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Tanggal</th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Model Unit</th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">CN Unit</th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Status</th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Temuan</th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Aksi</th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Aksi
                                                Case
                                            </th>
                                            <th class="py-2 text-uppercase small fw-semibold text-center">Action
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
                                                <td class="py-2 table-cell text-center">{{ $form['Tanggal'] ?? '-' }}</td>
                                                <td class="py-2 table-cell fw-semibold text-center">{{ $form['Model Unit'] ?? '-' }}
                                                </td>
                                                <td class="py-2 table-cell text-center">{{ $form['CN Unit'] ?? '-' }}</td>
                                                <td class="py-2 table-cell text-center">
                                                    <span
                                                        class="badge bg-{{ $form['Status'] == 'Rejected' ? 'danger' : ($form['Status'] == 'Pending' ? 'warning text-dark' : 'success') }} rounded-pill px-3 py-2">
                                                        {{ $form['Status'] }}
                                                    </span>
                                                </td>
                                                <td class="py-3 text-center">
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
                                                
                                                    <div class="container-fluid text-center">
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
                                                            <span class="text-center fst-italic">Tidak ada temuan open.</span>
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
                                                {{-- <td class="py-2 table-cell">{{ $form['Status Case'] ?? '-' }}</td> --}}
                                                <td class="pe-4 py-2 action-buttons text-center">
                                                    <button
                                                        class="btn btn-sm btn-outline-primary rounded-pill "
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $form['ID'] }}"
                                                        data-bs-toggle="tooltip" title="Lihat detail formulir">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function () {
                                                            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
                                                            tooltipTriggerList.map(function (tooltipTriggerEl) {
                                                                return new bootstrap.Tooltip(tooltipTriggerEl)
                                                            });
                                                        });
                                                    </script>

                                                    @if (($form['Status'] ?? '') === 'Rejected')
                                                        <a href="{{ route('user.inspection.edit', $form['ID']) }}"
                                                            class="btn btn-warning btn-sm rounded-pill mb-1"
                                                            data-bs-toggle="tooltip" title="Edit formulir">
                                                            <i class="fas fa-edit me-1"></i>
                                                        </a>
                                                        <form action="{{ route('user.inspection.destroy', $form['ID']) }}"
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
                                                        <form action="{{ route('user.inspection.destroy', $form['ID']) }}"
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
                                                <td class="text-center pe-4 py-3 text-nowrap text-center">
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
                                                
                                                
                                                <style>
                                                    .form-label.text-truncate {
                                                        max-width: 100%;
                                                        overflow: hidden;
                                                        text-overflow: ellipsis;
                                                        white-space: nowrap;
                                                    }
                                                </style>
                                                
                                                <div class="modal fade" id="inspectionModal{{ $form['ID'] }}"
                                                     tabindex="-1"
                                                     aria-labelledby="inspectionModalLabel{{ $form['ID'] }}"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                                        <form action="{{ route('user.action.inspection', ['id' => $form['ID']]) }}"
                                                              method="POST">
                                                            @csrf
                                                            <div class="modal-content shadow-sm border-0 rounded-4">
                                                                <div class="modal-header bg-primary text-white rounded-top-4">
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
                                                                                return is_string($val) &&
                                                                                    strpos(trim($val), '{') === 0 &&
                                                                                    strpos($val, 'action') !== false;
                                                                            },
                                                                        );
                                                
                                                                        $options = [
                                                                            'CHECK',
                                                                            'INSTALL',
                                                                            'REPLACE',
                                                                            'MONITORING',
                                                                            'REPAIR',
                                                                        ];
                                                
                                                                        $colorMap = [
                                                                            'CHECK' => 'bg-secondary text-white',
                                                                            'INSTALL' => 'bg-warning text-dark',
                                                                            'REPLACE' => 'bg-danger text-white',
                                                                            'MONITORING' => 'bg-info text-dark',
                                                                            'REPAIR' => 'bg-success text-white',
                                                                        ];
                                                
                                                                        $groupA = [
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
                                                
                                                                        $groupB = [
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
                                                
                                                                        $groupC = [
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
                                                                        ];
                                                                    @endphp
                                                
                                                                    @foreach (['A. Check Level Lubricant & Coolant' => $groupA, 'B. Check Job Condition After Repair' => $groupB, 'C. Sub Component' => $groupC] as $groupLabel => $groupItems)
                                                                        <h6 class="mt-3 fw-bold">{{ $groupLabel }}</h6>
                                                                        <div class="row">
                                                                            @if ($groupLabel === 'C. Sub Component')
                                                                                @foreach ($groupItems as $key)
                                                                                    @if (!empty($form[$key]) && is_string($form[$key]) && substr(trim($form[$key]), 0, 1) === '[')
                                                                                        @php
                                                                                            $jsonData = json_decode($form[$key], true);
                                                                                        @endphp
                                                                                        @if (is_array($jsonData))
                                                                                            @foreach ($jsonData as $idx => $item)
                                                                                                <div class="col-md-3 mb-3">
                                                                                                    <label class="form-label fw-semibold text-truncate" title="{{ $key }} - {{ $item['temuan'] ?? '' }}">
                                                                                                        {{ $key }} - {{ $item['temuan'] ?? '' }}
                                                                                                    </label>
                                                                                                    <select name="actions[{{ $key }}_{{ $idx }}]"
                                                                                                            class="form-select form-select-sm {{ isset($item['action']) ? $colorMap[$item['action']] : '' }}">
                                                                                                        @foreach ($options as $action)
                                                                                                            <option value="{{ $action }}"
                                                                                                                    {{ (isset($item['action']) && $item['action'] == $action) ? 'selected' : '' }}>
                                                                                                                {{ $action }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                            @endforeach
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                            @else
                                                                                @foreach ($groupItems as $key)
                                                                                    @if (isset($form[$key]) && is_string($form[$key]) && strpos($form[$key], '"action"') !== false)
                                                                                        @php
                                                                                            $jsonData = json_decode($form[$key], true);
                                                                                        @endphp
                                                                                        <div class="col-md-3 mb-3">
                                                                                            <label class="form-label fw-semibold text-truncate" title="{{ $key }}">
                                                                                                {{ $key }}
                                                                                            </label>
                                                                                            <select name="actions[{{ $key }}]"
                                                                                                    class="form-select form-select-sm {{ isset($jsonData['action']) ? $colorMap[$jsonData['action']] : '' }}">
                                                                                                @foreach ($options as $action)
                                                                                                    <option value="{{ $action }}"
                                                                                                            {{ (isset($jsonData['action']) && $jsonData['action'] == $action) ? 'selected' : '' }}>
                                                                                                        {{ $action }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                
                                                                    @php
                                                                        $groupedKeys = array_merge($groupA, $groupB, $groupC);
                                                                        $groupDData = $inspectionData->filter(function ($val, $key) use ($groupedKeys) {
                                                                            return !in_array($key, $groupedKeys);
                                                                        });
                                                                    @endphp
                                                
                                                                    @if ($groupDData->count())
                                                                        <h6 class="mt-3 fw-bold">Bagian D (Lainnya)</h6>
                                                                        <div class="row">
                                                                            @foreach ($groupDData as $key => $jsonValue)
                                                                                @php
                                                                                    $decoded = json_decode($jsonValue, true);
                                                                                    $selectedAction = strtoupper(old("actions.$key", $decoded['action'] ?? ''));
                                                                                    $colorClass = $colorMap[$selectedAction] ?? '';
                                                                                @endphp
                                                                                <div class="col-md-3 mb-3">
                                                                                    <label class="form-label fw-semibold text-truncate" title="{{ $key }}">
                                                                                        {{ $key }}
                                                                                    </label>
                                                                                    <select name="actions[{{ $key }}]"
                                                                                            class="form-select form-select-sm {{ $colorClass }}"
                                                                                            required>
                                                                                        @foreach ($options as $option)
                                                                                            <option value="{{ $option }}"
                                                                                                    {{ $selectedAction === $option ? 'selected' : '' }}>
                                                                                                {{ $option }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
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
                                                    const modalId = 'inspectionModal{{ $form['ID'] }}';
                                                    const modalElement = document.getElementById(modalId);
                                                
                                                    function applySelectColors(select) {
                                                        select.classList.remove(
                                                            'bg-secondary', 'bg-warning', 'bg-danger',
                                                            'bg-info', 'bg-success', 'text-white', 'text-dark'
                                                        );
                                                        switch (select.value) {
                                                            case 'CHECK':
                                                                select.classList.add('bg-secondary', 'text-white');
                                                                break;
                                                            case 'INSTALL':
                                                                select.classList.add('bg-warning', 'text-dark');
                                                                break;
                                                            case 'REPLACE':
                                                                select.classList.add('bg-danger', 'text-white');
                                                                break;
                                                            case 'MONITORING':
                                                                select.classList.add('bg-info', 'text-dark');
                                                                break;
                                                            case 'REPAIR':
                                                                select.classList.add('bg-success', 'text-white');
                                                                break;
                                                        }
                                                    }
                                                
                                                    modalElement.addEventListener('shown.bs.modal', function () {
                                                        const selects = modalElement.querySelectorAll('select[name^="actions"]');
                                                        selects.forEach(select => {
                                                            applySelectColors(select); // Apply colors on modal open
                                                            select.addEventListener('change', function () {
                                                                applySelectColors(this); // Update colors on change
                                                            });
                                                        });
                                                    });
                                                </script>
                                                
                                                
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
                        'UNDERCARRIAGE'
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
                                        <p class="mb-1 text-muted small">Tanggal</p>
                                        <p class="mb-0 fw-bold">{{ $form['Tanggal'] ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <p class="mb-1 text-muted small">Status</p>
                                        <span
                                            class="badge bg-{{ $form['Status'] == 'Rejected' ? 'danger' : ($form['Status'] == 'Pending' ? 'warning text-dark' : 'success') }} rounded-pill px-3 py-2">
                                            {{ $form['Status'] }}
                                        </span>
                                    </div>
                                </div>
                                {{-- Tampilkan data selain inspection keys, tanggal , dan status --}}
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle">
                                        <tbody>
                                            @foreach ($form as $key => $value)
                                                @if (!in_array($key, $allInspectionKeys) && $key != 'Tanggal' && $key != 'Status' && !in_array($key, $componentKeys))
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
        @else
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3 text-center py-5">
                        <div class="card-body">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h4 class="fw-bold mb-3">Belum Ada Formulir</h4>
                            <p class="text-muted mb-0">Anda belum mengirimkan formulir Inspeksi. Mulai dengan membuat
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
                    var cnUnitCell = row.cells[2]; // CN Unit berada di kolom ke-4 (0-indexed = 3)
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

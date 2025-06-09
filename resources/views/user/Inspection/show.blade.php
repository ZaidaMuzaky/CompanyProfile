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
                                            <th class="py-2 text-uppercase small fw-semibold">Tanggal Service</th>
                                            <th class="py-2 text-uppercase small fw-semibold">Model Unit</th>
                                            <th class="py-2 text-uppercase small fw-semibold">CN Unit</th>
                                            <th class="py-2 text-uppercase small fw-semibold">Status</th>
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
                                                {{-- <td class="py-2 table-cell">{{ $form['Status Case'] ?? '-' }}</td> --}}
                                                <td class="pe-4 py-2 action-buttons text-center">
                                                    <button class="btn btn-sm btn-outline-primary rounded-pill mb-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $index }}"
                                                        data-bs-toggle="tooltip" title="Lihat detail formulir">
                                                        <i class="fas fa-eye me-1"></i>
                                                    </button>

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
                                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                                        <form
                                                            action="{{ route('user.action.inspection', ['id' => $form['ID']]) }}"
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
                                                                        <h6 class="mt-3 fw-bold">{{ $groupLabel }}
                                                                        </h6>
                                                                        <div class="row">
                                                                            @foreach ($inspectionData as $key => $jsonValue)
                                                                                @if (in_array($key, $groupItems))
                                                                                    @php
                                                                                        $decoded = json_decode(
                                                                                            $jsonValue,
                                                                                            true,
                                                                                        );
                                                                                        $selectedAction = strtoupper(
                                                                                            old(
                                                                                                "actions.$key",
                                                                                                $decoded['action'] ??
                                                                                                    '',
                                                                                            ),
                                                                                        );
                                                                                        $colorClass =
                                                                                            $colorMap[
                                                                                                $selectedAction
                                                                                            ] ?? '';
                                                                                    @endphp

                                                                                    <div class="col-md-6 mb-3">
                                                                                        <div
                                                                                            class="d-flex align-items-center justify-content-between">
                                                                                            <div class="flex-grow-1 pe-3 fw- text-truncate"
                                                                                                title="{{ $key }}">
                                                                                                {{ $key }}
                                                                                            </div>
                                                                                            <div style="min-width: 140px;">
                                                                                                <select
                                                                                                    name="actions[{{ $key }}]"
                                                                                                    class="form-select form-select-sm {{ $colorClass }}"
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
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    @endforeach

                                                                    {{-- Group D (yang tidak masuk A/B/C) --}}
                                                                    @php
                                                                        $groupedKeys = array_merge(
                                                                            $groupA,
                                                                            $groupB,
                                                                            $groupC,
                                                                        );
                                                                        $groupDData = $inspectionData->filter(function (
                                                                            $val,
                                                                            $key
                                                                        ) use ($groupedKeys) {
                                                                            return !in_array($key, $groupedKeys);
                                                                        });

                                                                    @endphp

                                                                    @if ($groupDData->count())
                                                                        <h6 class="mt-3 fw-bold">Bagian D (Lainnya)</h6>
                                                                        <div class="row">
                                                                            @foreach ($groupDData as $key => $jsonValue)
                                                                                @php
                                                                                    $decoded = json_decode(
                                                                                        $jsonValue,
                                                                                        true,
                                                                                    );
                                                                                    $selectedAction = strtoupper(
                                                                                        old(
                                                                                            "actions.$key",
                                                                                            $decoded['action'] ?? '',
                                                                                        ),
                                                                                    );
                                                                                    $colorClass =
                                                                                        $colorMap[$selectedAction] ??
                                                                                        '';
                                                                                @endphp

                                                                                <div class="col-md-6 mb-3">
                                                                                    <div
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <div class="flex-grow-1 pe-3 fw-semibold text-truncate"
                                                                                            title="{{ $key }}">
                                                                                            {{ $key }}
                                                                                        </div>
                                                                                        <div style="min-width: 140px;">
                                                                                            <select
                                                                                                name="actions[{{ $key }}]"
                                                                                                class="form-select form-select-sm {{ $colorClass }}"
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
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                        class="btn btn-primary fw-semibold px-4">
                                                                        <i class="bi bi-save2 me-1"></i> Simpan Semua
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-outline-secondary fw-semibold px-4"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>


                                                <script>
                                                    const modalId = 'inspectionModal{{ $form['ID'] }}';
                                                    const modalElement = document.getElementById(modalId);

                                                    modalElement.addEventListener('shown.bs.modal', function() {
                                                        modalElement.querySelectorAll('select[name^="actions"]').forEach(select => {
                                                            select.addEventListener('change', function() {
                                                                this.classList.remove(
                                                                    'bg-secondary', 'bg-warning', 'bg-danger',
                                                                    'bg-info', 'bg-success', 'text-white', 'text-dark'
                                                                );
                                                                switch (this.value) {
                                                                    case 'CHECK':
                                                                        this.classList.add('bg-secondary', 'text-white');
                                                                        break;
                                                                    case 'INSTALL':
                                                                        this.classList.add('bg-warning', 'text-dark');
                                                                        break;
                                                                    case 'REPLACE':
                                                                        this.classList.add('bg-danger', 'text-white');
                                                                        break;
                                                                    case 'MONITORING':
                                                                        this.classList.add('bg-info', 'text-dark');
                                                                        break;
                                                                    case 'REPAIR':
                                                                        this.classList.add('bg-success', 'text-white');
                                                                        break;
                                                                }
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
                        'UNDERGRADUATE',
                        'WORK EQUIPMENT',
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
                                                @if (!in_array($key, $allInspectionKeys) && $key != 'Tanggal Service' && $key != 'Status')
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
<div class="table-responsive mb-4">
    <table class="table table-bordered table-striped align-middle">
        <tbody>
            @foreach ($componentKeys as $key)
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

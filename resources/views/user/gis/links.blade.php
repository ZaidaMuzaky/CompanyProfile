@extends('layouts.logapp')

@section('title', 'Spreadsheet CN Unit')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('user.cn-units.index') }}">CN Unit</a></li>
    <li class="breadcrumb-item active">{{ $unit->name }}</li>
@endsection

@section('content')
<div class="container">
    <h4 class="mb-3">Spreadsheet untuk CN Unit: {{ $unit->name }}</h4>

    @if (collect($links)->isEmpty())
    <p class="text-muted">Tidak ada link untuk CN Unit ini.</p>
@else
    <div class="row" id="spreadsheetList">
        @foreach ($links as $index => $link)
            @php
                $spreadsheetId = null;
                if (preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $link->spreadsheet_link, $matches)) {
                    $spreadsheetId = $matches[1];
                }
            @endphp
            <div class="col-12 mb-4">
                <div class="card h-100 shadow-sm rounded border-0">
                    <div class="card-body d-flex flex-column flex-md-row align-items-center bg-light gap-3">
                        <div class="d-flex align-items-center justify-content-center text-white rounded-circle flex-shrink-0"
                             style="width: 50px; height: 50px; background: linear-gradient(135deg, #28a745, #1e7e34); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                            <i class="bi bi-file-earmark-excel-fill" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="text-center text-md-start w-100">
                            <h5 class="card-title mb-1 fw-semibold">{{ $link->description ?? 'Link Spreadsheet' }}</h5>
                            <a href="{{ $link->spreadsheet_link }}" target="_blank" class="btn btn-sm btn-primary w-100 w-md-auto">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
</div>
@endsection

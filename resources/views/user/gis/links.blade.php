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
        <p>Tidak ada link untuk CN Unit ini.</p>
    @else
        <ul class="list-group">
            @foreach ($links as $index => $link)
                @php
                    $spreadsheetId = null;
                    if (preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $link->spreadsheet_link, $matches)) {
                        $spreadsheetId = $matches[1];
                    }
                    $pdfUrl = $spreadsheetId 
                        ? "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/export?format=pdf"
                        : null;
                @endphp

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $link->description ?? 'Link Spreadsheet' }}</strong><br>
                        <a href="{{ $link->spreadsheet_link }}" target="_blank">
                            {{ $link->spreadsheet_link }}
                        </a>
                    </div>
                    @if ($pdfUrl)
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#pdfModal{{ $index }}">
                            <i class="bi bi-eye"></i> Lihat PDF
                        </button>
                    @endif
                </li>

                <!-- Modal PDF Viewer -->
                @if ($pdfUrl)
                <div class="modal fade" id="pdfModal{{ $index }}" tabindex="-1" aria-labelledby="pdfModalLabel{{ $index }}" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pdfModalLabel{{ $index }}">Preview PDF - {{ $link->description ?? 'Spreadsheet' }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <iframe src="{{ $pdfUrl }}" width="100%" height="600px" style="border: none;"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </ul>
    @endif
</div>
@endsection

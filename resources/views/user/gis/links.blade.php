@extends('layouts.logapp')

@section('title', 'File CN Unit')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('user.cn-units.index') }}">CN Unit</a></li>
    <li class="breadcrumb-item active">{{ $unit->name }}</li>
@endsection

@section('content')
<div class="container">
    <h4 class="mb-3">File untuk CN Unit: {{ $unit->name }}</h4>

    @if ($files->isEmpty())
        <p class="text-muted">Tidak ada file untuk CN Unit ini.</p>
    @else
        <div class="row" id="fileList">
            @foreach ($files as $file)
                <div class="col-12 mb-4">
                    <div class="card h-100 shadow-sm rounded border-0">
                        <div class="card-body d-flex flex-column flex-md-row align-items-center bg-light gap-3">
                            <div class="d-flex align-items-center justify-content-center text-white rounded-circle flex-shrink-0"
     style="width: 50px; height: 50px; background: linear-gradient(135deg, #28a745, #1e7e34); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
    <i class="bi bi-file-earmark-excel-fill" style="font-size: 1.5rem;"></i>
</div>

                            <div class="text-center text-md-start w-100">
                                <h5 class="card-title mb-1 fw-semibold">{{ $file->file_name }}</h5>
                                <a href="{{ asset('storage/' . $file->file_path) }}" download class="btn btn-sm btn-success w-100 w-md-auto">
                                    <i class="bi bi-download me-1"></i> Download
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

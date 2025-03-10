@extends('layouts.logapp')

@section('title', 'Managemen Files PDF')

@section('content')
    <div class="container">
        <h2>Select Folder</h2>
        <div class="row">
            @foreach ($folders as $folder)
                <div class="col-md-4">
                    <div class="card mb-3 shadow-sm"
                        onclick="window.location='{{ route('user.files.show', $folder->id_folder) }}'"
                        style="cursor: pointer;">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                @if ($folder->icon_path)
                                    <img src="{{ asset('storage/' . $folder->icon_path) }}" alt="Folder Icon"
                                        style="width: 50px; height: auto;">
                                @else
                                    <i class="bi bi-folder-fill" style="font-size: 2rem; color: #fbc02d;"></i>
                                @endif
                            </div>
                            <div>
                                <h5 class="card-title mb-1">{{ $folder->nama }}</h5>
                                <small class="text-muted">{{ $folder->subfolders->count() }} subfolders</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

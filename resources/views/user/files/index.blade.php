@extends('layouts.logapp')

@section('title', 'Managemen Files PDF')

@section('breadcrumb')
    <li class="breadcrumb-item active">Folders</li>
@endsection

@section('content')
    <div class="container">
        <h2 class="fs-5">Select Folder</h2> <!-- Tambahkan fs-5 untuk ukuran font -->
        <div class="row g-3"> <!-- Tambahkan g-3 untuk margin antar card -->
            @foreach ($folders as $folder)
                <div class="col-6 col-sm-6 col-md-4"> <!-- 2 kolom di smartphone -->
                    <div class="card shadow-sm" onclick="window.location='{{ route('user.files.show', $folder->id_folder) }}'"
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
                                <h5 class="card-title mb-1 fs-6">{{ $folder->nama }}</h5> <!-- Tambahkan fs-6 -->
                                <small class="text-muted d-block fs-7">{{ $folder->subfolders->count() }} subfolders</small>
                                <!-- Tambahkan fs-7 -->
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

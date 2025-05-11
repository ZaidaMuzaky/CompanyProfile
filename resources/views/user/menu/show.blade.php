@extends('layouts.logapp')

@section('title', 'Files for ' . $submenu->nama)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('menus.view', $menu->id_menu) }}">{{ $menu->nama }}</a></li>
    <li class="breadcrumb-item active">{{ $submenu->nama }}</li>
@endsection

@section('content')
    <div class="container mt-4">
        <h4 class="mb-3">File untuk Submenu: <strong>{{ $submenu->nama }}</strong></h4>

        <div class="d-flex justify-content-center mb-3">
        </div>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>File</th>
                    <th>Deskripsi</th>
                    <th>Preview / Download</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($filesData as $index => $file)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <!-- Menampilkan ikon PDF -->
                            <i class="bi bi-file-earmark-pdf" style="font-size: 1.5rem;"></i>
                        </td>
                        <td>{{ $file->description }}</td>
                        <td>
                            <a href="{{ asset($file->path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-file-earmark-arrow-down"></i> Lihat File
                            </a>
                            <a href="{{ asset($file->path) }}" download class="btn btn-outline-success btn-sm">
                                <i class="bi bi-download"></i> Unduh
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada file tersedia untuk submenu ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
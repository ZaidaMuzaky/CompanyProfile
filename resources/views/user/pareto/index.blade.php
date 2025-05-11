@extends('layouts.logapp')

@section('title', 'File dari ' . $brand->nama)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Pareto Problem Unit</a></li>
    <li class="breadcrumb-item"><a href="#">{{ $brand->section->mainMenu->nama }}</a></li>
    <li class="breadcrumb-item"><a href="#">{{ $brand->section->nama }}</a></li>
    <li class="breadcrumb-item"><a href="#">{{ $brand->nama }}</a></li>
    <li class="breadcrumb-item active">File</li>
@endsection

@section('content')
    <div class="container">
        <h4 class="mb-4">File: <strong>{{ $brand->nama }}</strong></h4>

        <div class="d-flex justify-content-center mb-3">
            {{-- <form method="GET" action="{{ route('user.pareto.index', $brand->id) }}" class="d-flex" style="width: 60%;">
                <input type="text" name="search" class="form-control" placeholder="Search File..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form> --}}
        </div>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Preview / Download</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($brand->files as $index => $file)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $file->judul }}</td>
                        <td>{{ $file->deskripsi }}</td>
                        <td>
                            <a href="{{ asset('storage/' . $file->path) }}" class="btn btn-outline-primary btn-sm"
                                target="_blank">
                                <i class="bi bi-file-earmark-arrow-down"></i> Lihat File
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada file untuk brand ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
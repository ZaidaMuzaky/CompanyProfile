@extends('layouts.logapp')

@section('title', 'Manage CN Unit Files')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.cn-units.index') }}">CN Unit</a></li>
    <li class="breadcrumb-item active">{{ $unit->name }}</li>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3 flex-wrap">
        <h4>Daftar File untuk CN Unit: <strong>{{ $unit->name }}</strong></h4>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFileModal">
            <i class="bi bi-upload"></i> Upload File
        </button>
    </div>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama File</th>
                <th>Tipe</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($files as $index => $file)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">
                        {{ $file->file_name }}
                    </a>
                </td>
                <td>{{ $file->file_type }}</td>
                <td>{{ $file->description ?? '-' }}</td>
                <td class="d-flex gap-1">
                    <!-- Tombol Edit -->
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editDescModal{{ $file->id }}">
                        <i class="bi bi-pencil"></i> Edit
                    </button>

                    <!-- Form Hapus -->
                    <form action="{{ route('admin.cn-units.deleteFile', $file->id) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus file ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Belum ada file ditambahkan.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Upload File -->
<div class="modal fade" id="addFileModal" tabindex="-1" aria-labelledby="addFileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.cn-units.storeFile', $unit->id) }}"
              enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addFileModalLabel">Upload File untuk {{ $unit->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="file" class="form-label">Pilih File</label>
                    <input type="file" class="form-control" name="file" id="file" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi (opsional)</label>
                    <textarea class="form-control" name="description" id="description" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Deskripsi -->
@foreach ($files as $file)
<div class="modal fade" id="editDescModal{{ $file->id }}" tabindex="-1"
     aria-labelledby="editDescModalLabel{{ $file->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.cn-units.updateFile', $file->id) }}" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editDescModalLabel{{ $file->id }}">Edit Deskripsi File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ $file->description }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        @endif
    });
</script>
@endsection

@extends('layouts.logapp')

@section('title', 'Manage CN Unit Link')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.cn-units.index') }}">CN Unit</a></li>
    <li class="breadcrumb-item active">{{ $unit->name }}</li>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3 flex-wrap">
        <h4>Daftar Link untuk CN Unit: <strong>{{ $unit->name }}</strong></h4>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addLinkModal">
            <i class="bi bi-link-45deg"></i> Tambah Link
        </button>
    </div>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Link Spreadsheet</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($links as $index => $link)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <a href="{{ $link->spreadsheet_link }}" target="_blank">
                        {{ $link->spreadsheet_link }}
                    </a>
                </td>
                <td>{{ $link->description ?? '-' }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <button class="btn btn-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#editLinkModal{{ $link->id }}">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <form action="{{ route('admin.cn-units.deleteLink', $link->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus link ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </td>
                
            </tr>
        @empty
            <tr>
                <td colspan="4">Belum ada link ditambahkan.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    @foreach ($links as $link)
<!-- Modal Edit Link -->
<div class="modal fade" id="editLinkModal{{ $link->id }}" tabindex="-1" aria-labelledby="editLinkModalLabel{{ $link->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.cn-units.updateLink', $link->id) }}" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editLinkModalLabel{{ $link->id }}">Edit Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="spreadsheet_link{{ $link->id }}" class="form-label">Link Spreadsheet</label>
                    <input type="url" class="form-control" name="spreadsheet_link" id="spreadsheet_link{{ $link->id }}" value="{{ $link->spreadsheet_link }}" required>
                </div>
                <div class="mb-3">
                    <label for="description{{ $link->id }}" class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="description" id="description{{ $link->id }}" rows="2">{{ $link->description }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endforeach

</div>

<!-- Modal Tambah Link -->
<div class="modal fade" id="addLinkModal" tabindex="-1" aria-labelledby="addLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.cn-units.storeLink', $unit->id) }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addLinkModalLabel">Tambah Link untuk {{ $unit->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="spreadsheet_link" class="form-label">Link Spreadsheet</label>
                    <input type="url" class="form-control" name="spreadsheet_link" id="spreadsheet_link" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi (opsional)</label>
                    <textarea class="form-control" name="description" id="description" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

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

@extends('layouts.logapp')

@section('title', 'Audit Service Management')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Audit Service Management</li>
@endsection

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAuditModal">
                <i class="bi bi-folder-plus"></i> Add Sub Menu Audit
            </button>
            <form method="GET" action="{{ route('admin.audit.index') }}" class="d-flex mx-auto" style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search Sub Menu..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <table class="table mt-3">
            <tr>
                <th>No</th>
                <th>Nama Sub Menu</th>
                <th>Actions</th>
            </tr>
            @foreach ($subAudits as $index => $item)
                <tr onclick="window.location='{{ route('admin.audit.show', $item->id) }}'" style="cursor: pointer;">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>
                        <div class="d-flex gap-2" onclick="event.stopPropagation();">
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAuditModal"
                                onclick="editAudit('{{ $item->id }}', '{{ $item->nama }}')">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form action="{{ route('admin.audit.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addAuditModal" tabindex="-1" aria-labelledby="addAuditModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.audit.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAuditModalLabel">Add Sub Menu Audit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Sub Menu</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editAuditModal" tabindex="-1" aria-labelledby="editAuditModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="editAuditForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Sub Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editAuditId">
                        <div class="mb-3">
                            <label class="form-label">Nama Sub Menu</label>
                            <input type="text" class="form-control" id="editAuditName" name="nama" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editAudit(id, nama) {
            document.getElementById('editAuditId').value = id;
            document.getElementById('editAuditName').value = nama;
            document.getElementById('editAuditForm').action = "/admin/audit/" + id + "/update";
        }
    </script>
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
@endsection

@extends('layouts.logapp')

@section('title', 'Manage Parts')

@section('breadcrumb')
    <li class="breadcrumb-item active">Kategori</li>
@endsection

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPartModal">
                <i class="bi bi-folder-plus"></i> Add Part
            </button>
            <form method="GET" action="{{ route('admin.parts.index') }}" class="d-flex mx-auto" style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search Part..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <!-- Parts Table -->
        <table class="table mt-3">
            <tr>
                <th>No</th>
                <th>Nama Part</th>
                <th>Actions</th>
            </tr>
            @foreach ($categories as $index => $category)
                <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.parts.show', ['id' => $category->id]) }}'">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $category->name }}</td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPartModal"
                            onclick="editPart('{{ $category->id }}', '{{ $category->name }}')">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <!-- Delete Button -->
                        <form action="{{ route('admin.parts.destroy', $category->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Anda yakin akan menghapus part ini?');">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addPartModal" tabindex="-1" aria-labelledby="addPartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.parts.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPartModalLabel">Add Part</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="partName" class="form-label">Nama Part</label>
                            <input type="text" class="form-control" id="partName" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Part</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editPartModal" tabindex="-1" aria-labelledby="editPartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="editPartForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPartModalLabel">Edit Part</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editPartId" name="id">
                        <div class="mb-3">
                            <label for="editPartName" class="form-label">Nama Part</label>
                            <input type="text" class="form-control" id="editPartName" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editPart(id, name) {
            document.getElementById('editPartId').value = id;
            document.getElementById('editPartName').value = name;
            document.getElementById('editPartForm').action = "/admin/parts/" + id + "/update";
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
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
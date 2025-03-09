<!-- filepath: /d:/dp/CompanyD/resources/views/admin/folders/show.blade.php -->
@extends('layouts.logapp')

@section('title', 'Manage Subfolders of ' . $parentFolder->nama)

@section('content')
    <div class="container">
        <h2>Subfolders of {{ $parentFolder->nama }}</h2>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addSubfolderModal">Add Subfolder</button>

        <!-- Subfolders Table -->
        <table class="table mt-3">
            <tr>
                <th>No</th>
                <th>Nama Subfolder</th>
                <th>Icon</th>
                <th>Actions</th>
            </tr>
            @foreach ($subfolders as $index => $subfolder)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $subfolder->nama }}</td>
                    <td>
                        @if ($subfolder->icon_path)
                            <img src="{{ asset('storage/' . $subfolder->icon_path) }}" alt="Folder Icon"
                                style="width: 50px; height: auto;">
                        @else
                            <img src="{{ asset('assets/img/folder-icon.png') }}" alt="Folder Icon"
                                style="width: 50px; height: auto;">
                        @endif
                    </td>
                    <td>
                        <!-- Button to open Edit Modal -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSubfolderModal"
                            onclick="editSubfolder('{{ $subfolder->id_folder }}', '{{ $subfolder->nama }}', '{{ $subfolder->icon_path }}')">
                            Edit
                        </button>

                        <!-- Delete Form -->
                        <form action="{{ route('admin.folders.destroy', $subfolder->id_folder) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Anda yakin akan menghapus subfolder ini?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Add Subfolder Modal -->
    <div class="modal fade" id="addSubfolderModal" tabindex="-1" aria-labelledby="addSubfolderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubfolderModalLabel">Add Subfolder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addSubfolderForm" method="POST" action="{{ route('admin.folders.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $parentFolder->id_folder }}">
                        <div class="mb-3">
                            <label for="addSubfolderName" class="form-label">Nama Subfolder</label>
                            <input type="text" class="form-control" id="addSubfolderName" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="addSubfolderIcon" class="form-label">Subfolder Icon</label>
                            <input type="file" class="form-control" id="addSubfolderIcon" name="icon">
                        </div>
                        <button type="submit" class="btn btn-primary">Add Subfolder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Subfolder Modal -->
    <div class="modal fade" id="editSubfolderModal" tabindex="-1" aria-labelledby="editSubfolderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubfolderModalLabel">Edit Subfolder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editSubfolderForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editSubfolderId" name="id">

                        <div class="mb-3">
                            <label for="editSubfolderName" class="form-label">Nama Subfolder</label>
                            <input type="text" class="form-control" id="editSubfolderName" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="editSubfolderIcon" class="form-label">Subfolder Icon</label>
                            <input type="file" class="form-control" id="editSubfolderIcon" name="icon">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editSubfolder(id, nama, iconPath) {
            document.getElementById("editSubfolderId").value = id;
            document.getElementById("editSubfolderName").value = nama;

            let form = document.getElementById("editSubfolderForm");
            form.action = "/admin/folders/" + id;
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

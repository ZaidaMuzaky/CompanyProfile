@extends('layouts.logapp')

@section('title', 'Manage Folders')

@section('content')
    <div class="container">
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addFolderModal">Add Folder</button>
        <table class="table">
            <tr>
                <th>No</th>
                <th>Nama Folder</th>
                <th>Actions</th>
            </tr>
            @foreach ($folders as $index => $folder)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $folder->nama }}</td>
                    <td>
                        <!-- Button to open Edit Modal -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editFolderModal"
                            onclick="editFolder('{{ $folder->id_folder }}', '{{ $folder->nama }}')">
                            Edit
                        </button>

                        <!-- Delete Form -->
                        <form action="{{ route('admin.folders.destroy', $folder->id_folder) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Anda yakin akan menghapus folder ini?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Add Folder Modal -->
    <div class="modal fade" id="addFolderModal" tabindex="-1" aria-labelledby="addFolderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFolderModalLabel">Add Folder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addFolderForm" method="POST" action="{{ route('admin.folders.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="addFolderName" class="form-label">Nama Folder</label>
                            <input type="text" class="form-control" id="addFolderName" name="nama" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Folder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Folder Modal -->
    <div class="modal fade" id="editFolderModal" tabindex="-1" aria-labelledby="editFolderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFolderModalLabel">Edit Folder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editFolderForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editFolderId" name="id">

                        <div class="mb-3">
                            <label for="editFolderName" class="form-label">Nama Folder</label>
                            <input type="text" class="form-control" id="editFolderName" name="nama" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editFolder(id, nama) {
            document.getElementById("editFolderId").value = id;
            document.getElementById("editFolderName").value = nama;

            let form = document.getElementById("editFolderForm");
            form.action = "/admin/folders/" + id + "/update";
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

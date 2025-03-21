@extends('layouts.logapp')

@section('title', 'Manage Folders')

@section('content')
    <div class="container">
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addFolderModal"><i
                class="bi bi-folder-plus"></i> Add Folder</button>

        <!-- Parent Folders Table -->
        <table class="table mt-3">
            <tr>
                <th>No</th>
                <th>Nama Folder</th>
                <th>Icon</th>
                <th>Actions</th>
            </tr>
            @foreach ($folders as $index => $folder)
                <tr style="cursor: pointer;"
                    onclick="window.location='{{ route('admin.folders.show', ['id' => $folder->id_folder]) }}'">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $folder->nama }}</td>
                    <td>
                        @if ($folder->icon_path)
                            <img src="{{ asset('storage/' . $folder->icon_path) }}" alt="Folder Icon"
                                style="width: 50px; height: auto;">
                        @else
                            <img src="{{ asset('assets/img/folder-icon.png') }}" alt="Folder Icon"
                                style="width: 50px; height: auto;">
                        @endif
                    </td>
                    <td>
                        <!-- Edit Icon -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editFolderModal"
                            onclick="event.stopPropagation(); editFolder('{{ $folder->id_folder }}', '{{ $folder->nama }}', '{{ $folder->parent_id }}', '{{ $folder->icon_path }}')">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <!-- Delete Icon -->
                        <form action="{{ route('admin.folders.destroy', ['id' => $folder->id_folder]) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Anda yakin akan menghapus folder ini?'); event.stopPropagation();">
                                <i class="bi bi-trash"></i>
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
                    <form id="addFolderForm" method="POST" action="{{ route('admin.folders.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="addFolderName" class="form-label">Nama Folder</label>
                            <input type="text" class="form-control" id="addFolderName" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="addFolderIcon" class="form-label">Folder Icon</label>
                            <input type="file" class="form-control" id="addFolderIcon" name="icon">
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
                    <form id="editFolderForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editFolderId" name="id">

                        <div class="mb-3">
                            <label for="editFolderName" class="form-label">Nama Folder</label>
                            <input type="text" class="form-control" id="editFolderName" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="editFolderIcon" class="form-label">Folder Icon</label>
                            <input type="file" class="form-control" id="editFolderIcon" name="icon">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editFolder(id, nama, parentId, iconPath) {
            document.getElementById("editFolderId").value = id;
            document.getElementById("editFolderName").value = nama;

            let form = document.getElementById("editFolderForm");
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

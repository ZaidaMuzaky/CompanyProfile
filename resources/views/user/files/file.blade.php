<!-- filepath: /d:/dp/CompanyD/resources/views/user/files/file.blade.php -->
@extends('layouts.logapp')

@section('title', 'Managemen Files PDF - ' . $folder->nama)

@section('content')
    <div class="container">
        <h2>Files in {{ $folder->nama }}</h2>
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFileModal">Add File</button>
            <form method="GET" action="{{ route('user.files.manage', $folder->id_folder) }}" class="d-flex mx-auto"
                style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search files..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">Search</button>
            </form>
        </div>
        <table class="table">
            <tr>
                <th>No</th>
                <th>File Name</th>
                <th>Actions</th>
            </tr>
            @foreach ($files as $index => $file)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $file->nama_file }}</td>
                    <td>
                        <!-- Button to open Edit Modal -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editFileModal"
                            onclick="editFile('{{ $file->id_file }}', '{{ $file->nama_file }}', '{{ $file->id_folder }}')">
                            Edit
                        </button>

                        <!-- Button to open Detail Modal -->
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailFileModal"
                            onclick="detailFile('{{ $file->nama_file }}', '{{ $folder->nama }}', '{{ $file->user->username }}', '{{ $file->created_at }}', '{{ $file->updated_at }}')">
                            Detail
                        </button>

                        <!-- Button to open View Modal -->
                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#viewFileModal"
                            onclick="viewFile('{{ asset('storage/' . $file->path) }}')">
                            View
                        </button>

                        <!-- Delete Form -->
                        <form action="{{ route('user.files.destroy', $file->id_file) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </form>

                        <!-- Download Link -->
                        <a href="{{ route('user.files.download', $file->id_file) }}"
                            class="btn btn-primary btn-sm">Download</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Add File Modal -->
    <div class="modal fade" id="addFileModal" tabindex="-1" aria-labelledby="addFileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFileModalLabel">Add File to {{ $folder->nama }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addFileForm" method="POST" action="{{ route('user.files.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_folder" value="{{ $folder->id_folder }}">
                        <div class="mb-3">
                            <label for="addFile" class="form-label">File PDF</label>
                            <input type="file" class="form-control" id="addFile" name="file" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add File</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit File Modal -->
    <div class="modal fade" id="editFileModal" tabindex="-1" aria-labelledby="editFileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFileModalLabel">Edit File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editFileForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editFileId" name="id">
                        <input type="hidden" name="id_folder" value="{{ $folder->id_folder }}">

                        <div class="mb-3">
                            <label for="editFile" class="form-label">File PDF</label>
                            <input type="file" class="form-control" id="editFile" name="file" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail File Modal -->
    <div class="modal fade" id="detailFileModal" tabindex="-1" aria-labelledby="detailFileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailFileModalLabel">File Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nama File</th>
                            <td>:</td>
                            <td><span id="detailFileName"></span></td>
                        </tr>
                        <tr>
                            <th>Folder</th>
                            <td>:</td>
                            <td><span id="detailFolderName"></span></td>
                        </tr>
                        <tr>
                            <th>Di Upload Oleh</th>
                            <td>:</td>
                            <td><span id="detailUserName"></span></td>
                        </tr>
                        <tr>
                            <th>Waktu Dibuat</th>
                            <td>:</td>
                            <td><span id="detailCreatedAt"></span></td>
                        </tr>
                        <tr>
                            <th>Waktu Diupdate</th>
                            <td>:</td>
                            <td><span id="detailUpdatedAt"></span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- View File Modal -->
    <div class="modal fade" id="viewFileModal" tabindex="-1" aria-labelledby="viewFileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewFileModalLabel">View File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="pdfViewer" src="" width="100%" height="600px"></iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editFile(id, name, folderId) {
            document.getElementById("editFileId").value = id;
            document.getElementById("editFileForm").action = "/user/files/" + id + "/update";
        }

        function detailFile(fileName, folderName, username, createdAt, updatedAt) {
            document.getElementById("detailFileName").innerText = fileName;
            document.getElementById("detailFolderName").innerText = folderName;
            document.getElementById("detailUserName").innerText = username;
            document.getElementById("detailCreatedAt").innerText = createdAt;
            document.getElementById("detailUpdatedAt").innerText = updatedAt;
        }

        function viewFile(fileUrl) {
            document.getElementById("pdfViewer").src = fileUrl;
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

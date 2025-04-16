<!-- filepath: /d:/dp/CompanyD/resources/views/user/files/file.blade.php -->
@extends('layouts.logapp')

@section('title', 'Managemen Files PDF - ' . $folder->nama)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('user.files.index') }}">Folders</a></li>
    @php
        $currentFolder = $folder;
        $breadcrumbFolders = [];
        while ($currentFolder->parent) {
            $breadcrumbFolders[] = $currentFolder->parent;
            $currentFolder = $currentFolder->parent;
        }
        $breadcrumbFolders = array_reverse($breadcrumbFolders);
    @endphp
    @foreach ($breadcrumbFolders as $breadcrumbFolder)
        <li class="breadcrumb-item">
            <a href="{{ route('user.files.show', $breadcrumbFolder->id_folder) }}">{{ $breadcrumbFolder->nama }}</a>
        </li>
    @endforeach
    <li class="breadcrumb-item active">{{ $folder->nama }}</li>
@endsection

@section('content')
    <div class="container">
        <h2>Files in {{ $folder->nama }}</h2>
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFileModal">
                <i class="bi bi-plus-circle"></i> Add File
            </button>
            <form method="GET" action="{{ route('user.files.manage', $folder->id_folder) }}" class="d-flex mx-auto"
                style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search files..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
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
                        <!-- Edit Icon -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editFileModal"
                            onclick="editFile('{{ $file->id_file }}', '{{ $file->nama_file }}', '{{ $file->id_folder }}')">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <!-- Detail Icon -->
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailFileModal"
                            onclick="detailFile('{{ $file->nama_file }}', '{{ $folder->nama }}', '{{ $file->user->username }}', '{{ $file->created_at }}', '{{ $file->updated_at }}')">
                            <i class="bi bi-info-circle"></i>
                        </button>

                        <!-- View Icon -->
                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#viewFileModal"
                            onclick="viewFile('{{ asset('storage/' . $file->path) }}', '{{ pathinfo($file->path, PATHINFO_EXTENSION) }}')">
                            <i class="bi bi-eye"></i>
                        </button>

                        <!-- Delete Icon -->
                        <form action="{{ route('user.files.destroy', $file->id_file) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Anda Yakin Ingin Menghapus File Ini?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>

                        <!-- Download Icon -->
                        <a href="{{ route('user.files.download', $file->id_file) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-download"></i>
                        </a>
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
                    <form id="addFileForm" method="POST" action="{{ route('user.files.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_folder" value="{{ $folder->id_folder }}">
                        <div class="mb-3">
                            <label for="addFile" class="form-label">Files (PDF, Word, PPT)</label>
                            <input type="file" class="form-control" id="addFile" name="files[]" accept=".pdf,.doc,.docx,.ppt,.pptx" multiple required>
                        </div>
                        <!-- Progress bar -->
                        <div class="progress mb-2" style="height: 20px; display: none;" id="uploadProgressContainer">
                            <div class="progress-bar" id="uploadProgressBar" role="progressbar" style="width: 0%">0%</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add File</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar Modal -->
    <div class="modal fade" id="progressBarModal" tabindex="-1" aria-labelledby="progressBarModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="progressBarModalLabel">Uploading Files</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="progress">
                        <div id="uploadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    <p class="mt-2 text-center" id="uploadStatus">Preparing upload...</p>
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
                            <label for="editFile" class="form-label">Files (PDF, Word, PPT)</label>
                            <input type="file" class="form-control" id="editFile" name="files[]" accept=".pdf,.doc,.docx,.ppt,.pptx" multiple required>
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewFileModalLabel">View File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body position-relative">
                    <!-- Content dynamically added by viewFile function -->
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

        function viewFile(fileUrl, fileType) {
            const modalBody = document.querySelector("#viewFileModal .modal-body");
            modalBody.innerHTML = ""; // Clear previous content

            if (fileType === "pdf") {
                modalBody.innerHTML = `<iframe src="${fileUrl}" width="100%" height="800px" style="border: none;"></iframe>`;
            } else if (["doc", "docx", "ppt", "pptx"].includes(fileType)) {
                modalBody.innerHTML = `
                    <iframe src="https://docs.google.com/gview?url=${encodeURIComponent(fileUrl)}&embedded=true" 
                            width="100%" height="800px" style="border: none;"></iframe>`;
            } else {
                modalBody.innerHTML = `<span class="text-muted">Unsupported file format</span>`;
            }
        }

        document.getElementById("addFileForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();

            const progressBar = document.getElementById("uploadProgressBar");
            const progressContainer = document.getElementById("uploadProgressContainer");

            xhr.open("POST", form.action, true);
            xhr.setRequestHeader("X-CSRF-TOKEN", '{{ csrf_token() }}');

            // Show progress bar
            progressContainer.style.display = "block";

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percent + "%";
                    progressBar.innerText = percent + "%";
                }
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    progressBar.innerText = "Upload complete!";
                    setTimeout(() => location.reload(), 1000); // Refresh page
                } else {
                    Swal.fire('Upload Failed', 'Terjadi kesalahan saat mengunggah file.', 'error');
                }
            };

            xhr.onerror = function() {
                Swal.fire('Upload Failed', 'Koneksi terputus atau server tidak merespon.', 'error');
            };

            xhr.send(formData);
        });

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

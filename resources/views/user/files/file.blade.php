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
                            onclick="viewFile('{{ asset('storage/' . $file->path) }}')">
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
                <div class="modal-body position-relative">
                    <!-- Floating Zoom Buttons -->
                    <div class="zoom-controls position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                        <button id="zoomOut" class="btn btn-secondary btn-sm mb-2" title="Zoom Out">
                            <i class="bi bi-zoom-out"></i>
                        </button>
                        <button id="zoomIn" class="btn btn-secondary btn-sm" title="Zoom In">
                            <i class="bi bi-zoom-in"></i>
                        </button>
                    </div>
                    <!-- PDF Container -->
                    <div id="pdfContainer" style="overflow-y: auto; max-height: 600px;"></div>
                    <!-- Fallback Message -->
                    <div id="fallbackMessage" style="display: none;">
                        <p>Your browser does not support viewing PDF files. <a id="downloadLink" href="#" target="_blank">Click here to download the file.</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js"></script>

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

        let currentScale = 1.0; // Default zoom scale

        function viewFile(fileUrl) {
            const pdfContainer = document.getElementById("pdfContainer");
            const fallbackMessage = document.getElementById("fallbackMessage");
            const downloadLink = document.getElementById("downloadLink");

            // Save the file URL for zoom functionality
            pdfContainer.setAttribute("data-file-url", fileUrl);

            // Clear previous content
            pdfContainer.innerHTML = "";

            const loadingTask = pdfjsLib.getDocument(fileUrl);
            loadingTask.promise.then(function (pdf) {
                fallbackMessage.style.display = "none";

                // Render all pages
                for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                    pdf.getPage(pageNumber).then(function (page) {
                        const canvas = document.createElement("canvas");
                        canvas.style.marginBottom = "10px";
                        pdfContainer.appendChild(canvas);

                        const context = canvas.getContext("2d");
                        const viewport = page.getViewport({ scale: currentScale });

                        // Adjust for high DPI screens
                        const outputScale = window.devicePixelRatio || 1;
                        canvas.width = Math.floor(viewport.width * outputScale);
                        canvas.height = Math.floor(viewport.height * outputScale);
                        canvas.style.width = `${viewport.width}px`;
                        canvas.style.height = `${viewport.height}px`;

                        const transform = outputScale !== 1
                            ? [outputScale, 0, 0, outputScale, 0, 0]
                            : null;

                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport,
                            transform: transform,
                        };
                        page.render(renderContext);
                    });
                }
            }).catch(function (error) {
                console.error("Error loading PDF:", error);
                fallbackMessage.style.display = "block";
                downloadLink.href = fileUrl;
            });
        }

        // Zoom In and Zoom Out functionality
        document.getElementById("zoomIn").addEventListener("click", function () {
            currentScale += 0.1; // Increase scale
            applyZoom(currentScale); // Apply zoom without reloading
        });

        document.getElementById("zoomOut").addEventListener("click", function () {
            if (currentScale > 0.5) {
                currentScale -= 0.1; // Decrease scale
                applyZoom(currentScale); // Apply zoom without reloading
            }
        });

        function applyZoom(scale) {
            const pdfContainer = document.getElementById("pdfContainer");
            const canvases = pdfContainer.querySelectorAll("canvas");

            canvases.forEach((canvas) => {
                canvas.style.transform = `scale(${scale})`;
                canvas.style.transformOrigin = "top left"; // Set origin for scaling
            });
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

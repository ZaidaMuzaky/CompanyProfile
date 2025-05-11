@extends('layouts.logapp')

@section('title', 'Manage Files for ' . $submenu->nama)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Menus</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.menus.sub', $menu->id_menu) }}">{{ $menu->nama }}</a></li>
    <li class="breadcrumb-item active">{{ $submenu->nama }}</li>
@endsection

@section('content')
    <div class="container">

        <!-- Add File Button -->
        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFileModal">
                <i class="bi bi-plus-circle"></i> Add File
            </button>
        </div>

        <!-- Files Table -->
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>File</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($images as $index => $image)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if (in_array(pathinfo($image->image_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="File Image" style="width: 100px;">
                            @elseif (in_array(pathinfo($image->image_path, PATHINFO_EXTENSION), ['pdf']))
                                <i class="bi bi-file-earmark-pdf"></i> PDF
                            @else
                                <i class="bi bi-file-earmark"></i> File
                            @endif
                        </td>
                        <td>{{ $image->description }}</td>
                        <td>
                            <!-- View Button -->
                            <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank" class="btn btn-info btn-sm">
                                <i class="bi bi-eye"></i> 
                            </a>
                            <!-- Edit Button -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editFileModal"
                                onclick="setEditModal({{ $image->id }}, '{{ asset('storage/' . $image->image_path) }}', '{{ $image->description }}')">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <!-- Delete Form -->
                            <form
                                action="{{ route('admin.menus.sub.images.destroy', [$menu->id_menu, $submenu->id_submenu, $image->id]) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus file ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add File Modal -->
    <div class="modal fade" id="addFileModal" tabindex="-1" aria-labelledby="addFileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.menus.sub.images.store', [$menu->id_menu, $submenu->id_submenu]) }}"
                enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addFileModalLabel">Add File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="menuFile" class="form-label">Select File</label>
                        <input type="file" class="form-control" id="menuFile" name="file" required>
                    </div>
                    <div class="mb-3">
                        <label for="fileDescription" class="form-label">Description</label>
                        <textarea name="description" id="fileDescription" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add File
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit File Modal -->
    <div class="modal fade" id="editFileModal" tabindex="-1" aria-labelledby="editFileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editFileForm" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editFileModalLabel">Edit File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Current File</label>
                        <div id="currentFile" style="margin-bottom: 10px;"></div>
                    </div>
                    <div class="mb-3">
                        <label for="newFile" class="form-label">Select New File (optional)</label>
                        <input type="file" class="form-control" id="newFile" name="file">
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea name="description" id="editDescription" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function setEditModal(fileId, fileUrl, description) {
            const form = document.getElementById('editFileForm');
            form.action = `{{ url('admin/menus/' . $menu->id_menu . '/sub/' . $submenu->id_submenu . '/images') }}/${fileId}`;
            const fileExtension = fileUrl.split('.').pop().toLowerCase();

            const currentFileDiv = document.getElementById('currentFile');
            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                currentFileDiv.innerHTML = `<img src="${fileUrl}" alt="Current Image" style="width: 100%;">`;
            } else if (fileExtension === 'pdf') {
                currentFileDiv.innerHTML = `<i class="bi bi-file-earmark-pdf"></i> PDF`;
            } else {
                currentFileDiv.innerHTML = `<i class="bi bi-file-earmark"></i> File`;
            }

            document.getElementById('editDescription').value = description;
        }

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
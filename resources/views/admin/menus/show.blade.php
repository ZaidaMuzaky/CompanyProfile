@extends('layouts.logapp')

@section('title', 'Manage Images for ' . $submenu->nama)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Menus</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.menus.sub', $menu->id_menu) }}">{{ $menu->nama }}</a></li>
    <li class="breadcrumb-item active">{{ $submenu->nama }}</li>
@endsection

@section('content')
    <div class="container">

        <!-- Add Image Button -->
        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addImageModal">
                <i class="bi bi-plus-circle"></i> Add Image
            </button>
        </div>

        <!-- Images Table -->
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($images as $index => $image)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Menu Image" style="width: 100px;">
                        </td>
                        <td>{{ $image->description }}</td>
                        <td>
                            <!-- Edit Button -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editImageModal"
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
                                    onclick="return confirm('Yakin ingin menghapus gambar ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Image Modal -->
    <div class="modal fade" id="addImageModal" tabindex="-1" aria-labelledby="addImageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.menus.sub.images.store', [$menu->id_menu, $submenu->id_submenu]) }}"
                enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addImageModalLabel">Add Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="menuImage" class="form-label">Select Image</label>
                        <input type="file" class="form-control" id="menuImage" name="image" required>
                    </div>
                    <div class="mb-3">
                        <label for="imageDescription" class="form-label">Description</label>
                        <textarea name="description" id="imageDescription" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Image
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Image Modal -->
    <div class="modal fade" id="editImageModal" tabindex="-1" aria-labelledby="editImageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editImageForm" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editImageModalLabel">Edit Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <img id="currentImage" src="" alt="Current Image" style="width: 100%; margin-bottom: 10px;">
                    </div>
                    <div class="mb-3">
                        <label for="newImage" class="form-label">Select New Image (optional)</label>
                        <input type="file" class="form-control" id="newImage" name="image">
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
        function setEditModal(imageId, imageUrl, description) {
            const form = document.getElementById('editImageForm');
            form.action = `{{ url('admin/menus/' . $menu->id_menu . '/sub/' . $submenu->id_submenu . '/images') }}/${imageId}`;
            document.getElementById('currentImage').src = imageUrl;
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
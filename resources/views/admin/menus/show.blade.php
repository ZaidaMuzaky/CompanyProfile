{{-- filepath: resources/views/admin/menus/show.blade.php --}}
@extends('layouts.logapp')

@section('title', 'Manage Images for ' . $submenu->nama)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Menus</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.menus.sub', $menu->id_menu) }}">{{ $menu->nama }}</a></li>
    <li class="breadcrumb-item active">{{ $submenu->nama }}</li>
@endsection

@section('content')
<div class="container">

    <!-- Button to trigger Add Image Modal -->
    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addImageModal">
            <i class="bi bi-plus-circle"></i> Add Image
        </button>
    </div>

    <!-- Images Table -->
    <table class="table mt-3">
        <tr>
            <th>No</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        @foreach ($images as $index => $image)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <img src="{{ asset(str_replace('public', 'storage', $image)) }}" alt="Menu Image" style="width: 100px;">
                </td>
                <td>
                    <!-- Edit Button -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editImageModal"
                        onclick="setEditModal('{{ basename($image) }}', '{{ asset(str_replace('public', 'storage', $image)) }}')">
                        <i class="bi bi-pencil-square"></i> 
                    </button>

                    <!-- Delete Button -->
                    <form action="{{ route('admin.menus.sub.images.destroy', [$menu->id_menu, $submenu->id_submenu, basename($image)]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus gambar ini?')">
                            <i class="bi bi-trash"></i> 
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</div>

<!-- Add Image Modal -->
<div class="modal fade" id="addImageModal" tabindex="-1" aria-labelledby="addImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addImageModalLabel">Add Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.menus.sub.images.store', [$menu->id_menu, $submenu->id_submenu]) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="menuImage" class="form-label">Select Image</label>
                        <input type="file" class="form-control" id="menuImage" name="image" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Image
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Image Modal -->
<div class="modal fade" id="editImageModal" tabindex="-1" aria-labelledby="editImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editImageModalLabel">Edit Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editImageForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="currentImage" class="form-label">Current Image</label>
                        <img id="currentImage" src="" alt="Current Image" style="width: 100%; margin-bottom: 10px;">
                    </div>
                    <div class="mb-3">
                        <label for="newImage" class="form-label">Select New Image</label>
                        <input type="file" class="form-control" id="newImage" name="image" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function setEditModal(imageName, imageUrl) {
        const form = document.getElementById('editImageForm');
        form.action = `{{ url('admin/menus/' . $menu->id_menu . '/sub/' . $submenu->id_submenu . '/images') }}/${imageName}`;
        document.getElementById('currentImage').src = imageUrl;
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
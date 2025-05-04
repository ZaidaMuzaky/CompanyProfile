@extends('layouts.logapp')

@section('title', 'Manage Community')

@section('breadcrumb')
    <li class="breadcrumb-item active">Community</li>
@endsection

@section('content')

    <div class="container">
        <div class="d-flex justify-content-between mb-3 flex-wrap">
            <div class="d-flex flex-wrap">
                <!-- Add Community Button -->
                <button class="btn btn-success me-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addCommunityModal">
                    <i class="bi bi-plus-circle"></i>
                    <span class="d-none d-sm-inline ms-1">Add Community</span>
                </button>
            </div>
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.community.index') }}" class="d-flex mx-auto" style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search community..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <!-- Community Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($communities as $index => $community)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $community->title }}</td>
                        <td>
                            @if ($community->image)
                                <img src="{{ asset('storage/' . $community->image) }}" alt="Community Image" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                            <!-- Edit Button -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCommunityModal"
                                onclick="editCommunity('{{ $community->id }}', '{{ $community->title }}', '{{ $community->image ? asset('storage/' . $community->image) : '' }}')">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.community.destroy', $community->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this community item?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Community Modal -->
    <div class="modal fade" id="addCommunityModal" tabindex="-1" aria-labelledby="addCommunityModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCommunityModalLabel">Add Community</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addCommunityForm" method="POST" action="{{ route('admin.community.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="communityTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="communityTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="communityImage" class="form-label">Image</label>
                            <input type="file" class="form-control" id="communityImage" name="image" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Community Modal -->
    <div class="modal fade" id="editCommunityModal" tabindex="-1" aria-labelledby="editCommunityModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCommunityModalLabel">Edit Community</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCommunityForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editCommunityId" name="id">
                        <div class="mb-3">
                            <label for="editCommunityTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="editCommunityTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCommunityImage" class="form-label">Image</label>
                            <input type="file" class="form-control" id="editCommunityImage" name="image" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
        function editCommunity(id, title, imageUrl) {
            document.getElementById('editCommunityId').value = id;
            document.getElementById('editCommunityTitle').value = title;

            const imageInput = document.getElementById('editCommunityImage');
            const existingPreview = imageInput.parentNode.querySelector('img');
            if (existingPreview) {
                existingPreview.remove();
            }

            if (imageUrl) {
                const preview = document.createElement('img');
                preview.src = imageUrl;
                preview.alt = 'Current Image';
                preview.style.maxWidth = '100%';
                preview.style.marginTop = '10px';
                imageInput.parentNode.appendChild(preview);
            }

            const form = document.getElementById('editCommunityForm');
            form.action = `/admin/community/update/${id}`;
        }
    </script>

@endsection
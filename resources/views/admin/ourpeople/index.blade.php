@extends('layouts.logapp')

@section('title', 'Manage Our People')

@section('breadcrumb')
    <li class="breadcrumb-item active">Our People</li>
@endsection

@section('content')

    <div class="container">
        <div class="d-flex justify-content-between mb-3 flex-wrap">
            <div class="d-flex flex-wrap">
                <!-- Add Our People Button -->
                <button class="btn btn-success me-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addOurPeopleModal">
                    <i class="bi bi-plus-circle"></i>
                    <span class="d-none d-sm-inline ms-1">Add Our People</span>
                </button>
            </div>
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.people.index') }}" class="d-flex mx-auto" style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search our people..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <!-- Our People Table -->
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
                @foreach ($people as $index => $person)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $person->title }}</td>
                        <td>
                            @if ($person->image)
                                <img src="{{ asset('storage/' . $person->image) }}" alt="Our People Image" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                            <!-- Edit Button -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editOurPeopleModal"
                                onclick="editOurPeople('{{ $person->id }}', '{{ $person->title }}', '{{ $person->image ? asset('storage/' . $person->image) : '' }}')">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.people.destroy', $person->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this our people item?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Our People Modal -->
    <div class="modal fade" id="addOurPeopleModal" tabindex="-1" aria-labelledby="addOurPeopleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addOurPeopleModalLabel">Add Our People</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addOurPeopleForm" method="POST" action="{{ route('admin.people.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="ourPeopleTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="ourPeopleTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="ourPeopleImage" class="form-label">Image</label>
                            <input type="file" class="form-control" id="ourPeopleImage" name="image" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Our People Modal -->
    <div class="modal fade" id="editOurPeopleModal" tabindex="-1" aria-labelledby="editOurPeopleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOurPeopleModalLabel">Edit Our People</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editOurPeopleForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editOurPeopleId" name="id">
                        <div class="mb-3">
                            <label for="editOurPeopleTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="editOurPeopleTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="editOurPeopleImage" class="form-label">Image</label>
                            <input type="file" class="form-control" id="editOurPeopleImage" name="image" accept="image/*">
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
        function editOurPeople(id, title, imageUrl) {
            document.getElementById('editOurPeopleId').value = id;
            document.getElementById('editOurPeopleTitle').value = title;

            const imageInput = document.getElementById('editOurPeopleImage');
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

            const form = document.getElementById('editOurPeopleForm');
            form.action = `/admin/people/update/${id}`;
        }
    </script>

@endsection
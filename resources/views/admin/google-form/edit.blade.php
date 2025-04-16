<!-- filepath: d:\dp\CompanyD\resources\views\admin\google-form\edit.blade.php -->
@extends('layouts.logapp')

@section('title', 'Manage Google Form Links')

@section('breadcrumb')
    <li class="breadcrumb-item active">Manage Google Forms</li>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFormModal">
                <i class="bi bi-plus-circle"></i> Add New Form
            </button>
            <form method="GET" action="{{ route('admin.google-form.edit') }}" class="d-flex mx-auto" style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search form titles..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
        <table class="table">
            <tr>
                <th>Form</th>
                <th>URL</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            @forelse ($googleForms as $index => $form)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-file-earmark-text" style="font-size: 2rem;"></i>
                            </div>
                            <div>
                                <strong>{{ $form['title'] }}</strong><br>
                                {{ $form['description'] }}
                            </div>
                        </div>
                    </td>
                    <td><a href="{{ $form['url'] }}" target="_blank">{{ $form['url'] }}</a></td>
                    <td>
                        @if ($form['status'] === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editFormModal"
                            onclick="editForm('{{ $form['id'] }}', '{{ $form['title'] }}', '{{ $form['description'] }}', '{{ $form['status'] }}', '{{ $form['url'] }}')">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.google-form.delete', $form['id']) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this form?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No Google Forms available.</td>
                </tr>
            @endforelse
        </table>
    </div>

    <!-- Add Form Modal -->
    <div class="modal fade" id="addFormModal" tabindex="-1" aria-labelledby="addFormModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFormModalLabel">Add New Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm" method="POST" action="{{ route('admin.google-form.update') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" name="google_forms[0][title]" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="google_forms[0][description]" rows="2" required></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" name="google_forms[0][status]" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="url" class="form-label">URL</label>
                            <input type="url" class="form-control" name="google_forms[0][url]" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Form</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form Modal -->
    <div class="modal fade" id="editFormModal" tabindex="-1" aria-labelledby="editFormModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFormModalLabel">Edit Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="{{ route('admin.google-form.update') }}">
                        @csrf
                        <input type="hidden" id="editFormIndex" name="index">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="editTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="editStatus" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="url" class="form-label">URL</label>
                            <input type="url" class="form-control" id="editUrl" name="url" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Form</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let formIndex = 1;

        function editForm(id, title, description, status, url) {
            document.getElementById('editFormIndex').value = id; // Ensure the correct ID is set
            document.getElementById('editTitle').value = title;
            document.getElementById('editDescription').value = description;
            document.getElementById('editStatus').value = status;
            document.getElementById('editUrl').value = url;

            // Ensure the form action is correctly set for updating the specific form
            document.getElementById('editForm').action = "/admin/google-form/" + id;
        }

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

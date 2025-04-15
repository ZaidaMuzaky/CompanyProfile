@extends('layouts.logapp')

@section('title', 'Manage Users')

@section('breadcrumb')
    <li class="breadcrumb-item active">Users</li>
@endsection

@section('content')

    <div class="container">
        <h2>Manage Users</h2>
        <div class="d-flex justify-content-between mb-3">
            <div>
                <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-plus-circle"></i> Add User
                </button>
                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importUserModal">
                    <i class="bi bi-file-earmark-excel"></i> Import Users
                </button>
            </div>
            <form method="GET" action="{{ route('admin.users') }}" class="d-flex mx-auto" style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search usernames..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>


        <table class="table">
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            @foreach ($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->type }}</td>
                    <td>
                        <!-- Edit Icon -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal"
                            onclick="editUser('{{ $user->id_user }}', '{{ $user->username }}', '{{ $user->type }}')">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <!-- Delete Icon -->
                        <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Anda yakin akan menghapus pengguna ini?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="addUserName" class="form-label">Username</label>
                            <input type="text" class="form-control" id="addUserName" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="addUserPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="addUserPassword" name="password" minlength="8"
                                required>
                            <small class="text-danger d-none" id="addPasswordError">Password setidaknya harus 8
                                karakter</small>
                        </div>

                        <div class="mb-3">
                            <label for="addUserRole" class="form-label">Role</label>
                            <select class="form-control" id="addUserRole" name="role">
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" onclick="return validateAddPassword()">Add
                            User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Import User Modal -->
    <div class="modal fade" id="importUserModal" tabindex="-1" aria-labelledby="importUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importUserModalLabel">Import Users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Pastikan file Excel yang Anda upload sesuai dengan format yang ditentukan.</p>
                    <p>Anda dapat mengunduh template Excel di bawah ini:</p>
                    <a href="{{ asset('templates/user_import_template.xlsx') }}" class="btn btn-outline-success mb-3" download>
                        <i class="bi bi-download"></i> Download Template
                    </a>


                    <form id="importUserForm" method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="importUserFile" class="form-label">Upload Excel File</label>
                            <input type="file" class="form-control" id="importUserFile" name="file" accept=".xlsx, .xls" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" id="editUserId" name="id">

                        <div class="mb-3">
                            <label for="editUserName" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUserName" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="editUserPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="editUserPassword" name="password"
                                minlength="8">
                            <small class="text-danger d-none" id="editPasswordError">Password setidaknya harus 8
                                karakter</small>
                        </div>

                        <div class="mb-3">
                            <label for="editUserRole" class="form-label">Role</label>
                            <select class="form-control" id="editUserRole" name="role">
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary"
                            onclick="return validateEditPassword()">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function editUser(id, username, type) {
            document.getElementById("editUserId").value = id;
            document.getElementById("editUserName").value = username;
            document.getElementById("editUserRole").value = type;

            let form = document.getElementById("editUserForm");
            form.action = "/admin/users/" + id + "/update";
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
    <script>
        function validateAddPassword() {
            const password = document.getElementById('addUserPassword').value;
            const error = document.getElementById('addPasswordError');

            if (password.length < 8) {
                error.classList.remove('d-none');
                return false;
            }

            error.classList.add('d-none');
            return true;
        }

        function validateEditPassword() {
            const password = document.getElementById('editUserPassword').value;
            const error = document.getElementById('editPasswordError');

            if (password && password.length < 8) {
                error.classList.remove('d-none');
                return false;
            }

            error.classList.add('d-none');
            return true;
        }
    </script>

@endsection

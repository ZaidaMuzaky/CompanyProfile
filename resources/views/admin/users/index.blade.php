@extends('layouts.logapp')

@section('title', 'Manage Users')

@section('content')
    <div class="container">
        <h2>Manage Users</h2>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
        <table class="table">
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->type }}</td>
                    <td>
                        <!-- Button to open Edit Modal -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal"
                            onclick="editUser('{{ $user->id }}', '{{ $user->username }}', '{{ $user->password }}', '{{ $user->type }}')">
                            Edit
                        </button>

                        <!-- Delete Form -->
                        {{-- <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"                             style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </form> --}}
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
                            <input type="password" class="form-control" id="addUserPassword" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="addUserRole" class="form-label">Role</label>
                            <select class="form-control" id="addUserRole" name="role">
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Add User</button>
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
                            <input type="password" class="form-control" id="editUserPassword" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="editUserRole" class="form-label">Role</label>
                            <select class="form-control" id="editUserRole" name="role">
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function editUser(id, username, password, type) {
            document.getElementById("editUserId").value = id;
            document.getElementById("editUserName").value = username;
            document.getElementById("editUserPassword").value = password;
            document.getElementById("editUserRole").value = type;

            let form = document.getElementById("editUserForm");
            form.action = "/admin/users/" + id + "/update";
        }
    </script>
@endsection

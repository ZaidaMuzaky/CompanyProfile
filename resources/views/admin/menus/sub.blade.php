@extends('layouts.logapp')

@section('title', 'Manage Meca for ' . $menu->nama)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Menus</a></li>
    <li class="breadcrumb-item active">{{ $menu->nama }}</li>
@endsection

@section('content')
    <div class="container">

        <!-- Button to trigger Add Submenu Modal -->
        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubmenuModal">
                <i class="bi bi-plus-circle"></i> ADD ACH Meca
            </button>
        </div>

        <!-- Submenus Table -->
        <table class="table mt-3">
            <tr>
                <th>No</th>
                <th>Ach Meca</th>
                <th>Actions</th>
            </tr>
            @foreach ($submenus as $index => $submenu)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="cursor: pointer;" onclick="window.location='{{ route('admin.menus.show', [$menu->id_menu, $submenu->id_submenu]) }}'">
                        {{ $submenu->nama }}
                    </td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSubmenuModal"
                            onclick="setEditModal('{{ $submenu->id_submenu }}', '{{ $submenu->nama }}')">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <!-- Delete Button -->
                        <form action="{{ route('admin.menus.submenus.destroy', [$menu->id_menu, $submenu->id_submenu]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Add Submenu Modal -->
    <div class="modal fade" id="addSubmenuModal" tabindex="-1" aria-labelledby="addSubmenuModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubmenuModalLabel">Add ACH MECA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('admin.menus.submenus.store', $menu->id_menu) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="submenuName" class="form-label">MECA Name</label>
                            <input type="text" class="form-control" id="submenuName" name="nama" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add MECA</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Submenu Modal -->
    <div class="modal fade" id="editSubmenuModal" tabindex="-1" aria-labelledby="editSubmenuModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubmenuModalLabel">Edit ACH MECA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editSubmenuForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editSubmenuName" class="form-label">MECA Name</label>
                            <input type="text" class="form-control" id="editSubmenuName" name="nama" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

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

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 2000
            });
        @endif
    });

    function setEditModal(id, name) {
        const form = document.getElementById('editSubmenuForm');
        form.action = `{{ url('admin/menus/' . $menu->id_menu . '/sub') }}/${id}`;
        document.getElementById('editSubmenuName').value = name;
    }

    // Konfirmasi sebelum menghapus submenu
    document.querySelectorAll('form[action*="submenus.destroy"]').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
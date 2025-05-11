@extends('layouts.logapp')

@section('title', 'Manage Main Menus')

@section('breadcrumb')
    <li class="breadcrumb-item active">Main Menu</li>
@endsection

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMainMenuModal">
                <i class="bi bi-folder-plus"></i> Add Main Menu
            </button>
            <form method="GET" action="{{ route('admin.pareto.index') }}" class="d-flex mx-auto" style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search Main Menu..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <!-- Table -->
        <table class="table mt-3">
            <tr>
                <th>No</th>
                <th>Nama Main Menu</th>
                <th>Actions</th>
            </tr>
            @foreach ($mainMenus as $index => $mainMenu)
                <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.pareto.show', ['id' => $mainMenu->id]) }}'">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $mainMenu->nama }}</td>
                    <td>
                        <div class="d-flex gap-2" onclick="event.stopPropagation();">
                        <!-- Edit Button -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editMainMenuModal"
                            onclick="event.stopPropagation(); editMainMenu('{{ $mainMenu->id }}', '{{ $mainMenu->nama }}')">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <!-- Delete Button -->
                        <form action="{{ route('admin.pareto.destroy', $mainMenu->id) }}" method="POST" style="display:inline;"
                            onsubmit="event.stopPropagation(); return confirm('Yakin hapus menu ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        </div>
                    </td>
                </tr>
            @endforeach

        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addMainMenuModal" tabindex="-1" aria-labelledby="addMainMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.pareto.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMainMenuModalLabel">Add Main Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Main Menu</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editMainMenuModal" tabindex="-1" aria-labelledby="editMainMenuModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="editMainMenuForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMainMenuModalLabel">Edit Main Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editMenuId" name="id">
                        <div class="mb-3">
                            <label for="editMenuName" class="form-label">Nama Main Menu</label>
                            <input type="text" class="form-control" id="editMenuName" name="nama" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function editMainMenu(id, name) {
            document.getElementById('editMenuId').value = id;
            document.getElementById('editMenuName').value = name;
            document.getElementById('editMainMenuForm').action = "/admin/pareto/" + id + "/update";
        }
    </script>

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
    </script>
@endsection
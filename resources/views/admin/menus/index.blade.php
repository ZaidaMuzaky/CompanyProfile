{{-- filepath: resources/views/admin/menus/index.blade.php --}}
@extends('layouts.logapp')

@section('title', 'Manage Meca')

@section('breadcrumb')
    <li class="breadcrumb-item active">Meca</li>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3 flex-wrap">
        <div class="d-flex flex-wrap">
            <button class="btn btn-success me-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addMenuModal">
                <i class="bi bi-folder-plus"></i>
                <span class="d-none d-sm-inline ms-1">Add Meca</span>
            </button>
        </div>
        <form method="GET" action="{{ route('admin.menus.index') }}" class="d-flex mx-auto" style="width: 50%;">
            <input type="text" name="search" class="form-control" placeholder="Search meca..."
                value="{{ request()->query('search') }}">
            <button type="submit" class="btn btn-primary ms-2">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <table class="table mt-3">
        <tr>
            <th>No</th>
            <th>Nama Meca</th>
            <th>Actions</th>
        </tr>
        @foreach ($menus as $index => $menu)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="cursor: pointer;" onclick="window.location='{{ route('admin.menus.sub', $menu->id_menu) }}'">
                    {{ $menu->nama }}
                </td>
                <td>
                    <!-- Edit Button -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editMenuModal"
                        onclick="editMenu('{{ $menu->id_menu }}', '{{ $menu->nama }}')">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <!-- Delete Button -->
                    <form action="{{ route('admin.menus.destroy', $menu->id_menu) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus meca ini?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</div>

<!-- Add Menu Modal -->
<div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMenuModalLabel">Add Meca</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.menus.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="menuName" class="form-label">Nama Meca</label>
                        <input type="text" class="form-control" id="menuName" name="nama" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Meca</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Menu Modal -->
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMenuModalLabel">Edit Meca</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editMenuForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="editMenuName" class="form-label">Nama Meca</label>
                        <input type="text" class="form-control" id="editMenuName" name="nama" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update meca</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function editMenu(id, nama) {
        const form = document.getElementById('editMenuForm');
        form.action = `/admin/menus/${id}`;
        document.getElementById('editMenuName').value = nama;
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
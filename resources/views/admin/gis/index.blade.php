@extends('layouts.logapp')

@section('title', 'Manage CN Unit')

@section('breadcrumb')
    <li class="breadcrumb-item active">CN Unit</li>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3 flex-wrap">
        <div class="d-flex flex-wrap">
            <button class="btn btn-success me-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addCnUnitModal">
                <i class="bi bi-folder-plus"></i>
                <span class="d-none d-sm-inline ms-1">Add CN Unit</span>
            </button>
        </div>
        <form method="GET" action="{{ route('admin.cn-units.index') }}" class="d-flex mx-auto" style="width: 50%;">
            <input type="text" name="search" class="form-control" placeholder="Search CN Unit..."
                value="{{ request()->query('search') }}">
            <button type="submit" class="btn btn-primary ms-2">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama CN Unit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($cnUnits as $index => $unit)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="cursor: pointer;" onclick="window.location='{{ route('admin.cn-units.addLink', $unit->id) }}'">
                    {{ $unit->name }}
                </td>
                <td>
                    <!-- Edit Button -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCnUnitModal"
                        onclick="editCnUnit('{{ $unit->id }}', '{{ $unit->name }}')">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <!-- Delete Button -->
                    <form action="{{ route('admin.cn-units.destroy', $unit->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus CN Unit ini?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- Add CN Unit Modal -->
<div class="modal fade" id="addCnUnitModal" tabindex="-1" aria-labelledby="addCnUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.cn-units.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addCnUnitModalLabel">Add CN Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="cnName" class="form-label">Nama CN Unit</label>
                    <input type="text" class="form-control" id="cnName" name="name" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Add CN Unit</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit CN Unit Modal -->
<div class="modal fade" id="editCnUnitModal" tabindex="-1" aria-labelledby="editCnUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editCnUnitForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editCnUnitModalLabel">Edit CN Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editCnName" class="form-label">Nama CN Unit</label>
                    <input type="text" class="form-control" id="editCnName" name="name" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update CN Unit</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editCnUnit(id, name) {
        const form = document.getElementById('editCnUnitForm');
        form.action = `/admin/cn-units/${id}`;
        document.getElementById('editCnName').value = name;
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

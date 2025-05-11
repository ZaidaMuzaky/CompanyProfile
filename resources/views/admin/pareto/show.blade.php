@extends('layouts.logapp')

@section('title', 'Manage Menu Section')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.pareto.index') }}">Main Menu</a></li>
    <li class="breadcrumb-item active">Menu Section</li>
@endsection

@section('content')
    <div class="container">
        <h4 class="mb-4">Menu Sections for: <strong>{{ $mainMenu->nama }}</strong></h4>

        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                <i class="bi bi-folder-plus"></i> Add Menu Section
            </button>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.menuSections.index', $mainMenu->id) }}" class="d-flex mx-auto"
                style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search Menu Sections..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <!-- Menu Section Table -->
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Menu Section Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mainMenu->menuSections as $index => $section)
                    <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.menuSections.show', $section->id) }}'">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $section->nama }}</td>
                        <td>
                            <div class="d-flex gap-2" onclick="event.stopPropagation();">
                                <!-- Edit Button -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSectionModal"
                                    onclick="editSection('{{ $section->id }}', '{{ $section->nama }}')">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <!-- Delete Form -->
                                <form action="{{ route('admin.menuSections.destroy', $section->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus menu section ini?');">
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

            </tbody>
        </table>
    </div>

    <!-- Add Menu Section Modal -->
    <div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.menuSections.store') }}">
                    @csrf
                    <input type="hidden" name="main_menu_id" value="{{ $mainMenu->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Menu Section</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Menu Section Name</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Menu Section Modal -->
    <div class="modal fade" id="editSectionModal" tabindex="-1" aria-labelledby="editSectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="editSectionForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Menu Section</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editSectionId" name="id">
                        <div class="mb-3">
                            <label class="form-label">Menu Section Name</label>
                            <input type="text" class="form-control" id="editSectionName" name="nama" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editSection(id, name) {
            document.getElementById('editSectionId').value = id;
            document.getElementById('editSectionName').value = name;
            document.getElementById('editSectionForm').action = "{{ route('admin.menuSections.update', ['id' => '__id__']) }}".replace('__id__', id);
        }

        document.addEventListener("DOMContentLoaded", function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif
            });
    </script>
@endsection
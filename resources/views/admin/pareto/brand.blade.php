@extends('layouts.logapp')

@section('title', 'Manage Brand')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.pareto.index') }}">Main Menu</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.menuSections.index', $section->main_menu_id) }}">Menu Section</a>
    </li>
    <li class="breadcrumb-item active">Brand</li>
@endsection

@section('content')
    <div class="container">
        <h4 class="mb-4">Brands for Section: <strong>{{ $section->nama }}</strong></h4>

        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                <i class="bi bi-plus-circle"></i> Add Brand
            </button>

            <!-- Search (Opsional) -->
            <form method="GET" action="{{ route('admin.menuBrands.index', $section->id) }}" class="d-flex mx-auto"
                style="width: 50%;">
                <input type="text" name="search" class="form-control" value="{{ $search ?? '' }}" placeholder="Search Brands">

                <button type="submit" class="btn btn-primary ms-2"><i class="bi bi-search"></i></button>
            </form>

        </div>

        <!-- Brand Table -->
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Brand Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($brands as $index => $brand)
                    <tr onclick="window.location='{{ route('admin.pareto.main', $brand->id) }}'" style="cursor: pointer;">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $brand->nama }}</td>
                            <td>
                                <div class="d-flex gap-2" onclick="event.stopPropagation();">
                                    <!-- Edit Button -->
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editBrandModal"
                                        onclick="event.stopPropagation(); editBrand('{{ $brand->id }}', '{{ $brand->nama }}')">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.menuBrands.destroy', $brand->id) }}" method="POST"
                                        onsubmit="event.stopPropagation(); return confirm('Hapus brand ini?');">
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

    <!-- Add Brand Modal -->
    <div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.menuBrands.store') }}">
                    @csrf
                    <input type="hidden" name="menu_section_id" value="{{ $section->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Brand</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Brand Name</label>
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

    <!-- Edit Brand Modal -->
    <div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="editBrandForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Brand</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editBrandId" name="id">
                        <div class="mb-3">
                            <label class="form-label">Brand Name</label>
                            <input type="text" class="form-control" id="editBrandName" name="nama" required>
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
        function editBrand(id, name) {
            document.getElementById('editBrandId').value = id;
            document.getElementById('editBrandName').value = name;
            document.getElementById('editBrandForm').action = "{{ route('admin.menuBrands.update', ['id' => '__id__']) }}".replace('__id__', id);
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
@extends('layouts.logapp')

@section('title', 'Sub-Kategori dari ' . $category->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.parts.index') }}">Kategori</a></li>
    <li class="breadcrumb-item active">Sub-Kategori</li>
@endsection

@section('content')
    <div class="container">
        <h4 class="mb-4">Sub-Kategori: <strong>{{ $category->name }}</strong></h4>

        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSubModal">
                <i class="bi bi-folder-plus"></i> Tambah Sub-Kategori
            </button>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.subcategories.index', $category->id) }}" class="d-flex mx-auto"
                style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search Sub-Kategori..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>

        </div>

        <!-- Sub-kategori Table -->
        <table class="table mt-3">
            <tr>
                <th>No</th>
                <th>Nama Sub-Kategori</th>
                <th>Actions</th>
            </tr>
            @foreach ($subcategories as $index => $sub)
                <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.parts.main', ['sub_id' => $sub->id]) }}'">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $sub->name }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSubModal"
                                onclick="editSub('{{ $sub->id }}', '{{ $sub->name }}')">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <form action="{{ route('admin.subcategories.destroy', $sub->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus sub-kategori ini?');">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
            @endforeach
        </table>
    </div>

    <!-- Add Sub-Kategori Modal -->
    <div class="modal fade" id="addSubModal" tabindex="-1" aria-labelledby="addSubModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.subcategories.store') }}">
                    @csrf
                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Sub-Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Sub-Kategori</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Sub-Kategori Modal -->
    <div class="modal fade" id="editSubModal" tabindex="-1" aria-labelledby="editSubModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="editSubForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Sub-Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editSubId" name="id">
                        <div class="mb-3">
                            <label class="form-label">Nama Sub-Kategori</label>
                            <input type="text" class="form-control" id="editSubName" name="name" required>
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
        function editSub(id, name) {
            document.getElementById('editSubId').value = id;
            document.getElementById('editSubName').value = name;
            document.getElementById('editSubForm').action = "{{ route('admin.subcategories.update', ['category_id' => $category->id, 'id' => '__id__']) }}".replace('__id__', id);
            }


        document.addEventListener("DOMContentLoaded", function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif
            });
    </script>
@endsection
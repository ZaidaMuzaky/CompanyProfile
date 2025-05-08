@extends('layouts.logapp')

@section('title', 'Parts dari ' . $subcategory->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.parts.index') }}">Kategori</a></li>
    <li class="breadcrumb-item"><a
            href="{{ route('admin.subcategories.index', $subcategory->category_id) }}">Sub-Kategori</a></li>
    <li class="breadcrumb-item active">Parts</li>
@endsection

@section('content')
    <div class="container">
        <h4 class="mb-4">Parts dari Sub-Kategori: <strong>{{ $subcategory->name }}</strong></h4>

        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPartModal" style="margin-right: 1%">
                <i class="bi bi-plus-circle"></i> Tambah Part
            </button>
            <!-- Import Button -->
            <button class="btn btn-info d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#importPartModal">
                <i class="bi bi-file-earmark-excel"></i>
                <span class="d-none d-sm-inline ms-1">Import Parts</span>
            </button>

            <form method="GET" action="{{ route('admin.parts.main', $subcategory->id) }}" class="d-flex mx-auto"
                style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search Part..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>


        <!-- Tabel Parts -->
        <table class="table mt-3">
            <tr>
                <th>No</th>
                <th>Nama Sparepart</th>
                <th>Type</th>
                <th>Qty Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            @foreach ($parts as $index => $part)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $part->nama_sparepart }}</td>
                    <td>{{ $part->type }}</td>
                    <td>{{ $part->qty_stock }}</td>
                    <td>
                        <span class="badge bg-{{ $part->status == 'open' ? 'success' : 'secondary' }}">
                            {{ ucfirst($part->status) }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPartModal"
                            onclick="editPart({{ $part->id }}, '{{ $part->nama_sparepart }}', '{{ $part->type }}', '{{ $part->qty_stock }}', '{{ $part->status }}')">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <form action="{{ route('admin.parts.item.destroy', $part->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus part ini?');">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Modal Tambah Part -->
    <div class="modal fade" id="addPartModal" tabindex="-1" aria-labelledby="addPartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.parts.item.store') }}">
                    @csrf
                    <input type="hidden" name="subcategory_id" value="{{ $subcategory->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Part</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Sparepart</label>
                            <input type="text" class="form-control" name="nama_sparepart" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <input type="text" class="form-control" name="type" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Qty Stock</label>
                            <input type="number" class="form-control" name="qty_stock" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="open">Open</option>
                                <option value="close">Close</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Part -->
    <div class="modal fade" id="editPartModal" tabindex="-1" aria-labelledby="editPartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="editPartForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Part</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editPartId" name="id">
                        <div class="mb-3">
                            <label class="form-label">Nama Sparepart</label>
                            <input type="text" class="form-control" id="editPartName" name="nama_sparepart" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <input type="text" class="form-control" id="editPartType" name="type" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Qty Stock</label>
                            <input type="number" class="form-control" id="editPartQty" name="qty_stock" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" id="editPartStatus" name="status" required>
                                <option value="open">Open</option>
                                <option value="close">Close</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Import Parts -->
    <div class="modal fade" id="importPartModal" tabindex="-1" aria-labelledby="importPartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importPartModalLabel">Import Parts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Pastikan file Excel yang Anda upload sesuai dengan format yang ditentukan.</p>
                    <p>Anda dapat mengunduh template Excel di bawah ini:</p>
                    <a href="{{ asset('templates/part_import_template.xlsx') }}" class="btn btn-outline-success mb-3"
                        download>
                        <i class="bi bi-download"></i> Download Template
                    </a>

                    <form id="importPartForm" method="POST" action="{{ route('admin.parts.import') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="subcategory_id" value="{{ $subcategory->id }}">
                        <div class="mb-3">
                            <label for="importPartFile" class="form-label">Upload Excel File</label>
                            <input type="file" class="form-control" id="importPartFile" name="file" accept=".xlsx, .xls"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editPart(id, name, type, qty, status) {
            document.getElementById('editPartId').value = id;
            document.getElementById('editPartName').value = name;
            document.getElementById('editPartType').value = type;
            document.getElementById('editPartQty').value = qty;
            document.getElementById('editPartStatus').value = status;
            document.getElementById('editPartForm').action =
                "{{ route('admin.parts.item.update', ['id' => '__id__']) }}".replace('__id__', id);
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
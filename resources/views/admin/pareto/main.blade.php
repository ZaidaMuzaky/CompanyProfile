@extends('layouts.logapp')

@section('title', 'Pareto File Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.pareto.index') }}">Main Menu</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.menuSections.index', $section->main_menu_id) }}">Menu Section</a>
    </li>
    <li class="breadcrumb-item"><a href="{{ route('admin.menuBrands.index', $section->id) }}">Brand</a></li>
    <li class="breadcrumb-item active">File</li>
@endsection

@section('content')
    <div class="container">
        <h4 class="mb-4">File Management dari Brand: <strong>{{ $brand->nama }}</strong></h4>

        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFileModal" style="margin-right: 1%">
                <i class="bi bi-plus-circle"></i> Tambah File
            </button>

            <!-- Form Pencarian -->
            <form method="GET" action="{{ route('admin.menuFiles.index', ['menuBrand' => $brand->id]) }}"
                class="d-flex mx-auto" style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search File..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <!-- Tabel Files -->
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Tipe</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($files as $index => $file)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $file->judul }}</td>
                        <td>{{ $file->deskripsi }}</td>
                        <td>{{ $file->tipe }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editFileModal"
                                onclick="editFile({{ $file->id }}, '{{ $file->judul }}', '{{ $file->deskripsi }}', '{{ $file->tipe }}')">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form
                                action="{{ route('admin.menuFiles.destroy', ['menuBrand' => $brand->id, 'menuFile' => $file->id]) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus file ini?');">
                                    <i class="bi bi-trash"></i>
                                </button>   
                            </form>
                            <!-- Tombol View untuk melihat file atau gambar -->
                            @if ($file->tipe == 'file')
                                <a href="{{ asset('storage/' . $file->path) }}" class="btn btn-info btn-sm" target="_blank">
                                    <i class="bi bi-eye"></i> 
                                </a>
                            @elseif ($file->tipe == 'image')
                                <a href="{{ asset('storage/' . $file->path) }}" class="btn btn-info btn-sm" target="_blank">
                                    <i class="bi bi-eye"></i> 
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal Tambah File -->
        <div class="modal fade" id="addFileModal" tabindex="-1" aria-labelledby="addFileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.menuFiles.store', ['menuBrand' => $brand->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah File</h5>
                            <input type="hidden" name="menu_brand_id" value="{{ $brand->id }}">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Judul</label>
                                <input type="text" class="form-control" name="judul" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <input type="text" class="form-control" name="deskripsi" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tipe</label>
                                <select class="form-control" name="tipe" required>
                                    <option value="file">File</option>
                                    <option value="image">Image</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload File</label>
                                <input type="file" class="form-control" name="file" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit File -->
        <div class="modal fade" id="editFileModal" tabindex="-1" aria-labelledby="editFileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" id="editFileForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit File</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="editFileId" name="id">
                            <div class="mb-3">
                                <label class="form-label">Judul</label>
                                <input type="text" class="form-control" id="editFileName" name="judul" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <input type="text" class="form-control" id="editFileDesc" name="deskripsi" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tipe</label>
                                <select class="form-control" id="editFileType" name="tipe" required>
                                    <option value="file">File</option>
                                    <option value="image">Image</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload File</label>
                                <input type="file" class="form-control" name="file">
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
            function editFile(id, name, description, type) {
                document.getElementById('editFileId').value = id;
                document.getElementById('editFileName').value = name;
                document.getElementById('editFileDesc').value = description;
                document.getElementById('editFileType').value = type;
                document.getElementById('editFileForm').action = "{{ route('admin.menuFiles.update', ['menuBrand' => $brand->id, 'menuFile' => '__id__']) }}".replace('__id__', id);
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
    </div>
@endsection
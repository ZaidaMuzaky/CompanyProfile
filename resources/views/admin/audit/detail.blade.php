@extends('layouts.logapp')

@section('title', 'Audit Service Upload')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.audit.index') }}">Audit Service</a></li>
    <li class="breadcrumb-item active" aria-current="page">Audit Service Upload</li>
@endsection

@section('content')
    <div class="container">
        <h4 class="mb-4">Upload Audit: <strong>{{ $audit->nama }}</strong></h4>

        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUploadModal">
                <i class="bi bi-upload"></i> Upload Foto Audit
            </button>

            <form method="GET" action="{{ route('admin.audit.upload.index', $audit->id) }}" class="d-flex mx-auto"
                style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search Uploads..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>

        </div>




        <!-- Table Uploads -->
        <table class="table table-hover align-middle text-center mt-3">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Deskripsi</th>
                    <th>Tanggal Upload</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($audit->uploads as $index => $upload)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $upload->image_path) }}" alt="audit-img" width="100"
                                style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imagePreviewModal"
                                onclick="showImagePreview(this)">
                        </td>
                        <td>{{ $upload->description }}</td>
                        <td>{{ $upload->upload_date }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <!-- Edit -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editUploadModal"
                                    onclick="editUpload({{ $upload->id }}, '{{ $upload->description }}', '{{ $upload->upload_date }}')">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <!-- Delete -->
                                <form action="{{ route('admin.audit.upload.destroy', $upload->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus foto ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted">Belum ada foto yang diupload.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addUploadModal" tabindex="-1" aria-labelledby="addUploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.audit.upload.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="audit_id" value="{{ $audit->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Foto Audit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="image" class="form-label">Pilih Gambar</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="upload_date" class="form-label">Tanggal Upload</label>
                            <input type="date" name="upload_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editUploadModal" tabindex="-1" aria-labelledby="editUploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Tambahkan enctype untuk upload file -->
                <form method="POST" id="editUploadForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Upload</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editUploadId">

                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" id="edit_description" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_upload_date" class="form-label">Tanggal Upload</label>
                            <input type="date" name="upload_date" class="form-control" id="edit_upload_date"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_image" class="form-label">Ganti Gambar (kosongkan jika tidak ingin
                                mengganti)</label>
                            <input type="file" name="image" class="form-control" id="edit_image" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Image Preview Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- ukuran besar -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imagePreviewModalLabel">Preview Gambar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="previewImage" class="img-fluid" alt="Preview Image"
                        style="max-height: 70vh;">
                </div>
            </div>
        </div>
    </div>



    <!-- Scripts -->
    <script>
        function editUpload(id, description, date) {
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_upload_date').value = date;
            document.getElementById('editUploadForm').action = "/admin/audit/audit/upload/" + id + "/update";

            // Reset input file (kosongkan)
            document.getElementById('edit_image').value = '';
        }
    </script>
    <script>
        function showImagePreview(imgElement) {
            const src = imgElement.src;
            const previewImage = document.getElementById('previewImage');
            previewImage.src = src;
        }
    </script>

    <script>
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

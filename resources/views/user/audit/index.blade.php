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
            <form method="GET" action="{{ route('audit.view', $audit->id) }}" class="d-flex mx-auto" style="width: 50%;">
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-muted">Belum ada foto yang diupload.</td>
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

    <!-- Image Preview Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imagePreviewModalLabel">Preview Gambar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="previewImage" class="img-fluid" alt="Preview Image" style="max-height: 70vh;">
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function showImagePreview(imgElement) {
            const src = imgElement.src;
            const previewImage = document.getElementById('previewImage');
            previewImage.src = src;
        }
    </script>
@endsection

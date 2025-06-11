@extends('layouts.logapp')

@section('title', 'Edit Inspection Header')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Admin Inspection</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Inspection Header</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold text-dark">Edit Inspection Header</h2>
                <p class="text-muted mb-0">Perbarui gambar header untuk halaman Inspection.</p>
                <hr class="mt-3 mb-0">
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($header && $header->header_image)
                            <div class="mb-4">
                                <p class="mb-2 text-muted small fw-semibold">Gambar Saat Ini</p>
                                <img src="{{ Storage::url($header->header_image) }}" alt="Header Image"
                                    class="img-fluid rounded-3 shadow-sm" style="max-width: 400px;">
                            </div>
                        @else
                            <div class="mb-4 text-center">
                                <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada gambar header yang diunggah.</p>
                            </div>
                        @endif

                        <form action="{{ route('admin.inspection.header.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <label for="header_image" class="form-label fw-semibold">Upload Header Image</label>
                                <input type="file" name="header_image" id="header_image"
                                    class="form-control @error('header_image') is-invalid @enderror" accept="image/*">
                                @error('header_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted mt-1 d-block">Format yang didukung: JPG, PNG, max 20MB.</small>
                            </div>

                            <button type="submit" class="btn btn-primary rounded-pill px-4" data-bs-toggle="tooltip"
                                title="Perbarui gambar header">
                                <i class="fas fa-upload me-1"></i> Update Image
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .form-control {
            border-radius: 8px;
        }
        .form-label {
            font-size: 0.9rem;
            color: #495057;
        }
        .alert {
            border-radius: 8px;
        }
        .img-fluid {
            transition: transform 0.2s ease;
        }
        .img-fluid:hover {
            transform: scale(1.02);
        }
        @media (max-width: 768px) {
            .form-control,
            .btn {
                font-size: 0.9rem;
            }
            .img-fluid {
                max-width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        document.getElementById('header_image').addEventListener('change', function(e) {
            const [file] = e.target.files;
            if (file) {
                const preview = document.createElement('img');
                preview.src = URL.createObjectURL(file);
                preview.className = 'image-preview img-fluid rounded-3 shadow-sm mt-3';
                preview.style.maxWidth = '400px';
                const existingPreview = document.querySelector('.image-preview');
                if (existingPreview) existingPreview.remove();
                e.target.parentElement.appendChild(preview);
            }
        });
    </script>
@endpush

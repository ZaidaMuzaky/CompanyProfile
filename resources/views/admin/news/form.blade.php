@extends('layouts.logapp')

@section('title', $news->id ? 'Edit Berita' : 'Tambah Berita')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">Berita</a></li>
    <li class="breadcrumb-item active">{{ $news->id ? 'Edit Berita' : 'Tambah Berita' }}</li>
@endsection

@section('content')
    <div class="container">
        <h2 class="fs-5">{{ $news->id ? 'Edit Berita' : 'Tambah Berita' }}</h2>
        <form method="POST" action="{{ route('admin.news.storeOrUpdate') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $news->id }}">

            <div class="mb-3">
                <label for="newsTitle" class="form-label">Judul</label>
                <input type="text" class="form-control" id="newsTitle" name="judul" value="{{ old('judul', $news->judul) }}" required>
            </div>

            <div class="mb-3">
                <label for="newsContent" class="form-label">Konten</label>
                <textarea class="form-control" id="newsContent" name="konten" rows="5" required>{{ old('konten', $news->konten) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="newsImage" class="form-label">Gambar Thumbnail (opsional)</label>
                <input type="file" class="form-control" id="newsImage" name="gambar" accept="image/*">
                @if ($news->gambar)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $news->gambar) }}" alt="Thumbnail" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                @endif
                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
            </div>

            <button type="submit" class="btn btn-primary">{{ $news->id ? 'Update' : 'Simpan' }}</button>
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
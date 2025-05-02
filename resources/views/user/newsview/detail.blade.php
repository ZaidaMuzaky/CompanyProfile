@extends('layouts.logapp')

@section('title', 'Detail Berita')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('user.newsview.index') }}">Berita</a></li>
    <li class="breadcrumb-item active">{{ $news->judul }}</li>
@endsection

@section('content')
    <div class="container mt-4">
        @if ($news->gambar)
            <img src="{{ asset('storage/' . $news->gambar) }}" class="img-fluid mb-4" alt="{{ $news->judul }}"
                style="max-width: 600px; height: 400px; border-radius: 8px; display: block; margin: 0 auto; cursor: pointer;"
                data-bs-toggle="modal" data-bs-target="#imageModal">
        @else
            <!-- Gambar default jika tidak ada gambar -->
            <img src="{{ asset('assets/img/logoCP1.png') }}" class="img-fluid mb-4" alt="Default Thumbnail"
                style="max-width: 600px; height: auto; border-radius: 8px; display: block; margin: 0 auto;">
        @endif
        <h3 class="mb-4">{{ $news->judul }}</h3>
        <p class="text-muted">Diterbitkan pada: {{ $news->created_at->format('d M Y') }}</p>
        <p>{!! nl2br(e($news->konten)) !!}</p>
        <a href="{{ route('user.newsview.index') }}" class="btn btn-secondary mt-4">Kembali ke Daftar Berita</a>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">{{ $news->judul }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('storage/' . $news->gambar) }}" class="img-fluid" alt="{{ $news->judul }}">
                </div>
            </div>
        </div>
    </div>
@endsection
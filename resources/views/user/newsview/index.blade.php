@extends('layouts.logapp')

@section('title', 'Daftar Berita')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Daftar Berita</h2>
        <div class="row">
            @foreach ($news as $item)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        @if ($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" class="card-img-top" alt="{{ $item->judul }}">
                        @else
                            <!-- Gambar default jika tidak ada gambar -->
                            <img src="{{ asset('assets/img/logoCP1.png') }}" class="card-img-top" alt="Default Thumbnail">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->judul }}</h5>
                            <p class="card-text">{{ Str::limit($item->konten, 100, '...') }}</p>
                            <a href="{{ route('user.newsview.detail', $item->id) }}" class="btn btn-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
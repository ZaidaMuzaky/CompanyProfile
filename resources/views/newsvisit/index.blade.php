@extends('layouts.app')

@section('title', 'HPMU News')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Daftar Berita</h2>
        <div class="row">
            @foreach ($news as $item)
                <div class="col-md-4 mb-4">
                    <div class="card border-0 h-100">
                        @if ($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" class="card-img-top" alt="{{ $item->judul }}"
                                style="height: 300px; object-fit: cover;" />
                        @else
                            <img src="{{ asset('assets/img/logoCP1.png') }}" class="card-img-top" alt="Default Thumbnail"
                                style="height: 300px; object-fit: cover;" />
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $item->judul }}</h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($item->konten, 100, '...') }}</p>
                            <a href="{{ route('newsvisit.detail', $item->id) }}" class="btn btn-primary mt-auto">Baca
                                Selengkapnya</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
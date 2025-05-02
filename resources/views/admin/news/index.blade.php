@extends('layouts.logapp')

@section('title', 'Manajemen Berita')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">Berita</a></li>
    <li class="breadcrumb-item active">Manajemen Berita</li>
@endsection

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <!-- Tombol Tambah Berita -->
            <button class="btn btn-success me-2 d-flex align-items-center">
            <a href="{{ route('admin.news.createOrEdit') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Tambah Berita
            </a>
            </button>
            <form method="GET" action="{{ route('admin.news.index') }}" class="d-flex mx-auto" style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Cari berita..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <table class="table mt-3">
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($news as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->judul }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <a href="{{ route('admin.news.createOrEdit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Anda yakin ingin menghapus berita ini?')">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

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

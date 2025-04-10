<!-- filepath: /d:/dp/CompanyD/resources/views/user/files/show.blade.php -->
@extends('layouts.logapp')

@section('title', 'Managemen Files PDF - ' . $folder->nama)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('user.files.index') }}">Folders</a></li>
    <li class="breadcrumb-item active">{{ $folder->nama }}</li>
@endsection

@section('content')
    <div class="container mt-4">
        <h2>Select Subfolder in {{ $folder->nama }}</h2>
        
        <!-- Search Bar -->
        <div class="d-flex justify-content-center mb-3">
            <form method="GET" action="{{ route('user.files.show', $folder->id_folder) }}" class="d-flex"
                style="width: 50%;">
                <input type="text" id="subfolderSearch" name="search" class="form-control" placeholder="Search subfolders..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
        
        <div class="row justify-content-center">
            @foreach ($subfolders as $subfolder)
                <div class="col-6 col-sm-6 col-md-3 mb-3 subfolder-item">
                    <div class="card shadow-sm" style="cursor: pointer;"
                        onclick="window.location='{{ route('user.files.manage', $subfolder->id_folder) }}'">
                        <img src="{{ $subfolder->icon_path ? asset('storage/' . $subfolder->icon_path) : asset('assets/img/LogoUtama.png') }}"
                            class="card-img-top" alt="Folder Icon">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $subfolder->nama }}</h5>
                            <small class="text-muted">{{ $subfolder->files->count() }} items</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.getElementById('subfolderSearch').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            document.querySelectorAll('.subfolder-item').forEach(function(item) {
                const subfolderName = item.querySelector('.card-title').textContent.toLowerCase();
                item.style.display = subfolderName.includes(searchValue) ? '' : 'none';
            });
        });
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

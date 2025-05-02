@extends('layouts.logapp')

@section('title', 'Manage Achievements')

@section('breadcrumb')
    <li class="breadcrumb-item active">Achievements</li>
@endsection

@section('content')

    <div class="container">
        <div class="d-flex justify-content-between mb-3 flex-wrap">
            <div class="d-flex flex-wrap">
                <!-- Tombol Tambah Prestasi -->
                <button class="btn btn-success me-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addAchievementModal">
                    <i class="bi bi-plus-circle"></i>
                    <span class="d-none d-sm-inline ms-1">Add Achievement</span>
                </button>
            </div>
            <!-- Form Pencarian -->
            <form method="GET" action="{{ route('admin.achievement.index') }}" class="d-flex mx-auto" style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search achievements..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <!-- Tabel Prestasi -->
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($achievements as $index => $achievement)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $achievement->judul }}</td>
                        <td>{{ Str::limit($achievement->deskripsi, 50, '...') }}</td>
                        <td>
                            @if ($achievement->gambar)
                                <img src="{{ asset('storage/' . $achievement->gambar) }}" alt="Achievement Image" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAchievementModal"
                                onclick="editAchievement('{{ $achievement->id }}', '{{ $achievement->judul }}', '{{ $achievement->deskripsi }}', '{{ $achievement->gambar ? asset('storage/' . $achievement->gambar) : '' }}')">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.achievement.destroy', $achievement->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Anda yakin akan menghapus prestasi ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Achievement Modal -->
    <div class="modal fade" id="addAchievementModal" tabindex="-1" aria-labelledby="addAchievementModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAchievementModalLabel">Add Achievement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addAchievementForm" method="POST" action="{{ route('admin.achievement.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="achievementTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="achievementTitle" name="judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="achievementDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="achievementDescription" name="deskripsi" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="achievementImage" class="form-label">Image</label>
                            <input type="file" class="form-control" id="achievementImage" name="gambar" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Achievement Modal -->
    <div class="modal fade" id="editAchievementModal" tabindex="-1" aria-labelledby="editAchievementModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAchievementModalLabel">Edit Achievement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAchievementForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editAchievementId" name="id">
                        <div class="mb-3">
                            <label for="editAchievementTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="editAchievementTitle" name="judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAchievementDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editAchievementDescription" name="deskripsi" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editAchievementImage" class="form-label">Image</label>
                            <input type="file" class="form-control" id="editAchievementImage" name="gambar" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

        function editAchievement(id, title, description, imageUrl) {
            document.getElementById('editAchievementId').value = id;
            document.getElementById('editAchievementTitle').value = title;
            document.getElementById('editAchievementDescription').value = description;

            const imageInput = document.getElementById('editAchievementImage');
            const existingPreview = imageInput.parentNode.querySelector('img');
            if (existingPreview) {
                existingPreview.remove();
            }

            if (imageUrl) {
                const preview = document.createElement('img');
                preview.src = imageUrl;
                preview.alt = 'Current Image';
                preview.style.maxWidth = '100%';
                preview.style.marginTop = '10px';
                imageInput.parentNode.appendChild(preview);
            }

            const form = document.getElementById('editAchievementForm');
            form.action = `/admin/achievement/update/${id}`;
        }
    </script>

@endsection
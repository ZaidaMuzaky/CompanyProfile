@extends('layouts.logapp')

@section('title', 'Managemen Files PDF')

@section('content')
    <div class="container">
        <h2>Select Folder</h2>
        <div class="row">
            @foreach ($folders as $folder)
                <div class="col-md-4">
                    <div class="card mb-3 shadow-sm" onclick="selectFolder('{{ $folder->id_folder }}', '{{ $folder->nama }}')"
                        style="cursor: pointer;">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-folder-fill" style="font-size: 2rem; color: #fbc02d;"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">{{ $folder->nama }}</h5>
                                <small class="text-muted">{{ $folder->files->count() }} items</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Add File Modal -->
    <div class="modal fade" id="addFileModal" tabindex="-1" aria-labelledby="addFileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFileModalLabel">Add File to <span id="selectedFolderName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addFileForm" method="POST" action="{{ route('user.files.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="selectedFolderId" name="id_folder">
                        <div class="mb-3">
                            <label for="addFile" class="form-label">File PDF</label>
                            <input type="file" class="form-control" id="addFile" name="file" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add File</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectFolder(id, name) {
            window.location.href = '/user/files/' + id; // Arahkan ke halaman manipulasi file
        }
    </script>
@endsection

<!-- filepath: /d:/dp/CompanyD/resources/views/user/files/index.blade.php -->
@extends('layouts.logapp')

@section('title', 'Managemen Files PDF')

@section('content')
    <div class="container">
        <h2>Select Folder</h2>
        <ul class="list-group">
            @foreach ($folders as $folder)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $folder->nama }}
                    <button class="btn btn-primary btn-sm"
                        onclick="selectFolder('{{ $folder->id_folder }}', '{{ $folder->nama }}')">Select</button>
                </li>
            @endforeach
        </ul>
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
            document.getElementById("selectedFolderId").value = id;
            document.getElementById("selectedFolderName").innerText = name;
            var addFileModal = new bootstrap.Modal(document.getElementById('addFileModal'));
            addFileModal.show();
        }
    </script>
@endsection

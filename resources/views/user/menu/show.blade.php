@extends('layouts.logapp')

@section('title', 'Images for ' . $submenu->nama)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('menus.view', $menu->id_menu) }}">{{ $menu->nama }}</a></li>
    <li class="breadcrumb-item active">{{ $submenu->nama }}</li>
@endsection

@section('content')
<div class="container">
    <div class="row mt-4">
        @if (count($images) > 0)
            @foreach ($images as $image)
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="{{ asset(str_replace('public', 'storage', $image)) }}" class="card-img-top" alt="Image"
                             style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal"
                             onclick="showImageModal('{{ asset(str_replace('public', 'storage', $image)) }}')">
                    </div>
                </div>
            @endforeach
        @else
            <p>No images available for this submenu.</p>
        @endif
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Image" class="img-fluid">
            </div>
            <div class="modal-footer">
                <a id="downloadButton" href="#" class="btn btn-primary" download>
                    <i class="bi bi-download"></i> Download
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showImageModal(imageUrl) {
        const modalImage = document.getElementById('modalImage');
        const downloadButton = document.getElementById('downloadButton');
        modalImage.src = imageUrl;
        downloadButton.href = imageUrl;
    }
</script>
@endsection
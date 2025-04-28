@extends('layouts.logapp')

@section('title', 'Google Form')

@section('breadcrumb')
    <li class="breadcrumb-item active">Google Forms</li>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="mb-4">
            <input type="text" id="searchBar" class="form-control" placeholder="Search Google Forms..."
                onkeyup="filterForms()">
        </div>
        <div class="row" id="formList">
            @foreach ($googleForms as $form)
                @if ($form['status'] === 'active')
                    <div class="col-12 mb-4 form-item">
                        <div class="card h-100 shadow-sm rounded border-0">
                            <div class="card-body d-flex flex-column flex-md-row align-items-center bg-light gap-3">
                                <div class="d-flex align-items-center justify-content-center text-white rounded-circle flex-shrink-0"
                                    style="width: 50px; height: 50px; background: linear-gradient(135deg, #6f42c1, #5a32a3); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                    <i class="bi bi-file-earmark-text" style="font-size: 1.5rem;"></i>
                                </div>
                                <div class="text-center text-md-start w-100">
                                    <h5 class="card-title mb-1 fw-semibold">{{ $form['title'] }}</h5>
                                    <p class="card-text text-muted small mb-2">{{ $form['description'] }}</p>
                                    <a href="{{ $form['url'] }}" target="_blank" class="btn btn-sm btn-primary w-100 w-md-auto">
                                        <i class="bi bi-box-arrow-up-right"></i> Open
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <script>
        function filterForms() {
            const searchInput = document.getElementById('searchBar').value.toLowerCase();
            const formItems = document.querySelectorAll('.form-item');

            formItems.forEach(item => {
                const title = item.querySelector('.card-title').textContent.toLowerCase();
                const description = item.querySelector('.card-text').textContent.toLowerCase();

                if (title.includes(searchInput) || description.includes(searchInput)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
@endsection 
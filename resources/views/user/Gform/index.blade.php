@extends('layouts.logapp')

@section('title', 'Select Google Form')

@section('breadcrumb')
    <li class="breadcrumb-item active">Google Forms</li>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="mb-4">
            <input type="text" id="searchBar" class="form-control" placeholder="Search Google Forms..." onkeyup="filterForms()">
        </div>
        <div class="row" id="formList">
            @foreach ($googleForms as $form)
                @if ($form['status'] === 'active')
                    <div class="col-12 mb-4 form-item">
                        <div class="card h-100 shadow-sm rounded border-0">
                            <div class="card-body d-flex align-items-center bg-light">
                                <div class="icon-container d-flex align-items-center justify-content-center text-white rounded-circle" 
                                     style="width: 60px; height: 60px; margin-right: 1rem; background: linear-gradient(135deg, #6f42c1, #5a32a3); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                    <i class="bi bi-file-earmark-text" style="font-size: 2rem;"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0"><strong>{{ $form['title'] }}</strong></h5>
                                    <p class="card-text text-muted mt-0 mb-1">{{ $form['description'] }}</p>
                                    <a href="{{ $form['url'] }}" target="_blank" class="text-primary">
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

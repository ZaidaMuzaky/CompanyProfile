{{-- filepath: d:\dp\CompanyD\resources\views\admin\menus\view.blade.php --}}
@extends('layouts.logapp')

@section('title', 'View Submenus for ' . $menu->nama)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">{{ $menu->nama }}</li>
@endsection

@section('content')
<div class="container">

    <div class="row mt-4">
        @if ($menu->submenus->count() > 0)
            @foreach ($menu->submenus as $submenu)
                <div class="col-12 mb-4">
                    <div class="card d-flex flex-row align-items-center">
                        <!-- Icon Folder -->
                        <div class="p-3">
                            <i class="bi bi-folder-fill" style="font-size: 2rem; color: #007bff;"></i>
                        </div>
                        <!-- Card Content -->
                        <div class="card-body">
                            <h5 class="card-title">{{ $submenu->nama }}</h5>
                            <a href="{{ route('menus.sub.show', [$menu->id_menu, $submenu->id_submenu]) }}" class="btn btn-primary">
                                View File
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p>No submenus available for this menu.</p>
        @endif
    </div>
</div>
@endsection
@extends('layouts.logapp')

@section('title', 'Parts dari ' . $subcategory->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Kategori</a></li>
    <li class="breadcrumb-item"><a href="#">{{ $subcategory->category->name }}</a></li>
    <li class="breadcrumb-item active">Parts</li>
@endsection

@section('content')
    <div class="container">
        <h4 class="mb-4">Parts: <strong>{{ $subcategory->name }}</strong></h4>

        <div class="d-flex justify-content-center mb-3">
            <form method="GET" action="{{ route('user.parts.index', $subcategory->id) }}" class="d-flex"
                style="width: 60%;">
                <input type="text" name="search" class="form-control" placeholder="Search Part..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Sparepart</th>
                    <th>Type</th>
                    <th>Qty Stock</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($parts as $index => $part)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $part->nama_sparepart }}</td>
                        <td>{{ $part->type }}</td>
                        <td>{{ $part->qty_stock }}</td>
                        <td>
                            <span class="badge bg-{{ $part->status == 'open' ? 'success' : 'secondary' }}">
                                {{ ucfirst($part->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada part ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
@extends('layouts.logapp')

@section('title', 'Unschedule Parts')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Unschedule Parts</a></li>
@endsection

@section('content')
    <div class="container">
        <h4 class="mb-4">Data Unschedule Parts</h4>

        <div class="d-flex justify-content-center mb-3">
            <form method="GET" action="{{ route('user.partunschedule.index') }}" class="d-flex" style="width: 60%;">
                <input type="text" name="search" class="form-control" placeholder="Search Part..."
                    value="{{ request('search') }}">
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
                    <th>Tanggal</th>
                    <th>Type</th>
                    <th>Model</th>
                    <th>No Orderan</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($partunschedules as $index => $part)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $part->nama_sparepart }}</td>
                        <td>{{ $part->tanggal }}</td>
                        <td>{{ $part->type }}</td>
                        <td>{{ $part->model }}</td>
                        <td>{{ $part->no_orderan }}</td>
                        <td>{{ $part->keterangan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
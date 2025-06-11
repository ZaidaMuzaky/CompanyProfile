@extends('layouts.logapp')

@section('title', 'CN Unit')

@section('breadcrumb')
    <li class="breadcrumb-item active">CN Unit</li>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-center mb-3">
        <form method="GET" action="{{ route('user.cn-units.index') }}" class="d-flex" style="width: 60%;">
            <input type="text" name="search" class="form-control" placeholder="Search CN Unit..."
                value="{{ request()->query('search') }}">
            <button type="submit" class="btn btn-primary ms-2">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <table class="table mt-3 table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama CN Unit</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($cnUnits as $index => $unit)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="cursor: pointer;" onclick="window.location='{{ route('user.cn-units.links', $unit->id) }}'">
                    {{ $unit->name }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection

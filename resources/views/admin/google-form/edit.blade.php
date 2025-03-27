<!-- filepath: d:\dp\CompanyD\resources\views\admin\google-form\edit.blade.php -->
@extends('layouts.logapp')

@section('title', 'Edit Google Form Link')

@section('breadcrumb')
    <li class="breadcrumb-item active">Edit Google Form</li>
@endsection

@section('content')
    <div class="container mt-4">
        <h2>Edit Google Form Link</h2>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('admin.google-form.update') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="google_form_link" class="form-label">Google Form Link</label>
                <input type="url" class="form-control" id="google_form_link" name="google_form_link"
                    value="{{ $googleFormLink }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Link</button>
        </form>
    </div>
@endsection

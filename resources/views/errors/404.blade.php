<!-- filepath: /d:/dp/CompanyD/resources/views/errors/404.blade.php -->
@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
    <div class="container text-center">
        <h1 class="display-1">404</h1>
        <h2>Page Not Found</h2>
        <p>Sorry, the page you are looking for could not be found.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Go to Home</a>
    </div>
@endsection

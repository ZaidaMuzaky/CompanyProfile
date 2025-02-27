<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyApp')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>@yield('title', 'Dashboard') - NiceAdmin</title>

        <!-- Favicons -->
        <link href="{{ asset('assets2/img/favicon.png') }}" rel="icon">
        <link href="{{ asset('assets2/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

        <!-- Google Fonts -->
        <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
            rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link href="{{ asset('assets2/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets2/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
        <link href="{{ asset('assets2/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets2/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
        <link href="{{ asset('assets2/css/style.css') }}" rel="stylesheet">
    </head>

    <body>
        @include('partials.navbarapp')

        <main id="main" class="main">
            {{-- <div class="pagetitle">
                <h1>@yield('title')</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </nav>
            </div><!-- End Page Title --> --}}

            <section class="section dashboard">
                <div class="row">
                    @yield('content')
                </div>
            </section>
        </main><!-- End #main -->

        {{-- <footer id="footer" class="footer">
            <div class="copyright">
                &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
            </div>
        </footer><!-- End Footer --> --}}

        <!-- Vendor JS Files -->
        <script src="{{ asset('assets2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets2/js/main.js') }}"></script>


    </body>

    </html>

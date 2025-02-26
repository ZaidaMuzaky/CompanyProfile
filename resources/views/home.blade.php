@extends('layouts.app')

@section('content')
    <!-- Home Section -->
    <section id="home" class="hero section">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up">
                    <h1>Welcome to FlexStart</h1>
                    <p data-aos="fade-up" data-aos-delay="100">Your one-stop solution for web design and development.</p>
                    <div data-aos="fade-up" data-aos-delay="200">
                        <a href="{{ url('/about') }}" class="btn-get-started">Get Started</a>
                    </div>
                </div>
                <div class="col-lg-6 hero-img" data-aos="zoom-out">
                    <img src="{{ asset('assets/img/hero-img.png') }}" class="img-fluid animated" alt="Hero Image">
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Perusahan Section -->
    <section id="tentang" class="about section">
        <div class="container" data-aos="fade-up">
            <div class="row gx-0">
                <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="content">
                        <h3>Who We Are</h3>
                        <h2>Expedita voluptas omnis cupiditate totam eveniet nobis sint iste.</h2>
                        <p>
                            We are a team of passionate developers and designers committed to delivering top-notch digital
                            solutions.
                        </p>
                        <div class="text-center text-lg-start">
                            <a href="#"
                                class="btn-read-more d-inline-flex align-items-center justify-content-center align-self-center">
                                <span>Read More</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
                    <img src="{{ asset('assets/img/about.png') }}" class="img-fluid" alt="About Image">
                </div>
            </div>
        </div>
    </section>

    <!-- Produk Section -->
    <section id="produk" class="values section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Our Values</h2>
                <p>What drives us to provide the best services</p>
            </div>
            <div class="row">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card">
                        <img src="{{ asset('assets/img/values-1.png') }}" class="img-fluid" alt="Value 1">
                        <h3>Innovation</h3>
                        <p>We constantly innovate to bring you the best solutions.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card">
                        <img src="{{ asset('assets/img/values-2.png') }}" class="img-fluid" alt="Value 2">
                        <h3>Commitment</h3>
                        <p>We are committed to delivering excellence in every project.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card">
                        <img src="{{ asset('assets/img/values-3.png') }}" class="img-fluid" alt="Value 3">
                        <h3>Quality</h3>
                        <p>Our services are designed to meet the highest standards.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

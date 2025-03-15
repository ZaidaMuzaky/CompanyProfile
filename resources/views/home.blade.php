@extends('layouts.app')

@section('content')
    <!-- Home Section -->
    <section id="home" class="hero section">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up">
                    <h1>PT. Hasta Panca Mandiri Utama Site KDA</h1>
                    <p data-aos="fade-up" data-aos-delay="100">Mitra terpercaya Anda dalam layanan penambangan sejak 2016.</p>
                    {{-- <div data-aos="fade-up" data-aos-delay="200">
                        <a href="{{ url('/about') }}" class="btn-get-started">Mulai Sekarang</a>
                    </div> --}}
                </div>
                {{-- <div class="col-lg-6 hero-img" data-aos="zoom-out">
                    <img src="{{ asset('assets/img/hero-img.png') }}" class="img-fluid animated" alt="Gambar Hero">
                </div> --}}
            </div>
        </div>
    </section>

    <!-- Tentang Perusahaan Section -->
    <section id="tentang" class="about section">
        <div class="container" data-aos="fade-up">
            <div class="row gx-0">
                <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="content">
                        <h3>Siapa Kami</h3>
                        <h2>PT. Hasta Panca Mandiri Utama Site KDA</h2>
                        <p>
                            Didirikan pada tahun 2016 di Ketapang, Kalimantan Barat, kami adalah perusahaan jasa penambangan
                            yang berkomitmen untuk mencapai Keunggulan Pemeliharaan (Maintenance Excellence - ME) dalam
                            operasi kami. Komitmen kami terhadap perbaikan berkelanjutan memastikan bahwa kami
                            mempertahankan
                            standar tertinggi dalam manajemen penambangan.
                        </p>
                        <p>
                            Strategi Pemeliharaan kami dirancang untuk memastikan proses yang aman dan standar, yang
                            menghasilkan
                            kinerja peralatan yang optimal dan daya tahan yang tinggi. Hal ini memungkinkan kami untuk
                            melakukan
                            operasi penambangan secara efisien dan dapat diandalkan, mendukung kesehatan keseluruhan
                            perusahaan.
                        </p>
                        {{-- <div class="text-center text-lg-start">
                            <a href="#"
                                class="btn-read-more d-inline-flex align-items-center justify-content-center align-self-center">
                                <span>Baca Selengkapnya</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div> --}}
                    </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
                    <img src="{{ asset('assets/img/cp-img.jpg') }}" class="img-fluid" alt="Gambar Tentang Kami">
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan Section -->
    <section id="departemen" class="values section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Departemen Kami</h2>
                <p>Departemen kunci yang mendukung operasi penambangan kami</p>
            </div>
            <div class="row">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card">
                        <img src="{{ asset('assets/img/gearplant.png') }}" alt="Gear Plant">
                        <h3>Departemen Plant</h3>
                        <p>Departemen kunci untuk proses pemeliharaan dalam operasi penambangan.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card">
                        <img src="{{ asset('assets/img/dt.png') }}" alt="dt">
                        <h3>Section DT</h3>
                        <p>Bertanggung jawab atas pemeliharaan peralatan berat.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card">
                        <img src="{{ asset('assets/img/cr.png') }}" alt="cr">
                        <h3>Section A2B</h3>
                        <p>Fokus pada dukungan operasional kegiatan penambangan.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="card">
                        <img src="{{ asset('assets/img/sp.png') }}" alt="sp">
                        <h3>Section Support</h3>
                        <p>Memberikan layanan dukungan penting untuk operasi penambangan.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="card">
                        <img src="{{ asset('assets/img/hr.png') }}" alt="hr">
                        <h3>Section WP</h3>
                        <p>Menangani pemeliharaan fasilitas bengkel.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="card">
                        <img src="{{ asset('assets/img/hrp.png') }}" alt="hrp">
                        <h3>Section Fabrikasi</h3>
                        <p>Bertanggung jawab atas fabrikasi dan perbaikan peralatan penambangan.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="700">
                    <div class="card">
                        <img src="{{ asset('assets/img/box.png') }}" alt="box">
                        <h3>Section Comex</h3>
                        <p>Fokus pada kegiatan komersial dan ekspor.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="800">
                    <div class="card">
                        <img src="{{ asset('assets/img/tr.png') }}" alt="tr">
                        <h3>Section Tyre</h3>
                        <p>Spesialis dalam pemeliharaan dan manajemen ban untuk peralatan berat.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

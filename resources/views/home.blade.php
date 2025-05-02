@extends('layouts.app')

@section('content')
    <!-- Home Section -->
    <section id="home" class="hero section">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up">
                    <h1>PT. Hasta Panca Mandiri Utama Site KDA</h1>
                    <p data-aos="fade-up" data-aos-delay="100">Mitra terpercaya Anda dalam layanan penambangan sejak 2016.</p>
                </div>
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
                            operasi kami.
                        </p>
                        <p>
                            Strategi Pemeliharaan kami dirancang untuk memastikan proses yang aman dan standar, yang
                            menghasilkan kinerja peralatan yang optimal dan daya tahan yang tinggi.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
                    <img src="{{ asset('assets/img/cp-img.jpg') }}" class="img-fluid" alt="Gambar Tentang Kami">
                </div>
            </div>
        </div>
    </section>
    
    <!-- Achievements Section -->
    <section id="achievements" class="achievements section">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>Achievements</h2>
                <p>Pencapain dan Prestasi Kami</p>
            </div>

            <style>
                #achievementCarousel .carousel-control-prev-icon,
                #achievementCarousel .carousel-control-next-icon {
                    background-color: black;
                }

                #achievementCarousel .carousel-indicators [data-bs-target] {
                    background-color: black;
                }
            </style>

            <div id="achievementCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($achievements->chunk(3) as $chunkIndex => $chunk)
                        <div class="carousel-item @if ($chunkIndex === 0) active @endif">
                            <div class="row">
                                @foreach ($chunk as $achievement)
                                    <div class="col-md-4">
                                        <div class="card">
                                            <img src="{{ asset('storage/' . $achievement->gambar) }}" class="card-img-top" alt="{{ $achievement->judul }}">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $achievement->judul }}</h5>
                                                <p class="card-text">{{ $achievement->deskripsi }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#achievementCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#achievementCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Layanan Section -->
    <section id="departemen" class="values section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Section Kami</h2>
                <p>Section yang mendukung operasi penambangan kami</p>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 gy-4"> <!-- Tambahkan gy-4 untuk margin antar card -->
                <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card card-small">
                        <img src="{{ asset('assets/img/gearplant.png') }}" alt="Gear Plant">
                        <h3>Departemen Plant</h3>
                        <p>Departemen kunci untuk proses pemeliharaan dalam operasi penambangan.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card card-small">
                        <img src="{{ asset('assets/img/dt.png') }}" alt="dt">
                        <h3>Section DT</h3>
                        <p>Bertanggung jawab atas pemeliharaan peralatan berat.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card card-small">
                        <img src="{{ asset('assets/img/cr.png') }}" alt="cr">
                        <h3>Section A2B</h3>
                        <p>Fokus pada dukungan operasional kegiatan penambangan.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="card card-small">
                        <img src="{{ asset('assets/img/sp.png') }}" alt="sp">
                        <h3>Section Support</h3>
                        <p>Memberikan layanan dukungan penting untuk operasi penambangan.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="card card-small">
                        <img src="{{ asset('assets/img/hr.png') }}" alt="hr">
                        <h3>Section WP</h3>
                        <p>Menangani pemeliharaan fasilitas bengkel.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="card card-small">
                        <img src="{{ asset('assets/img/hrp.png') }}" alt="hrp">
                        <h3>Section Fabrikasi</h3>
                        <p>Bertanggung jawab atas fabrikasi dan perbaikan peralatan penambangan.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="700">
                    <div class="card card-small">
                        <img src="{{ asset('assets/img/box.png') }}" alt="box">
                        <h3>Section Comex</h3>
                        <p>Fokus pada kegiatan komersial dan ekspor.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="800">
                    <div class="card card-small">
                        <img src="{{ asset('assets/img/tr.png') }}" alt="tr">
                        <h3>Section Tyre</h3>
                        <p>Spesialis dalam pemeliharaan dan manajemen ban untuk peralatan berat.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal for Image Zoom -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Preview" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalImage = document.getElementById('modalImage');
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));

            document.querySelectorAll('.card-img-top').forEach(img => {
                img.addEventListener('click', function () {
                    modalImage.src = this.src;
                    imageModal.show();
                });
            });
        });
    </script>
@endsection

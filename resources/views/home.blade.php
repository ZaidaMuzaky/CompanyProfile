@extends('layouts.app')

@section('content')
            <!-- Home Section -->
            <section id="home" class="hero section position-relative overflow-hidden" style="min-height: 100vh;">
            <!-- Background Slide Container -->
            <div class="bg-slider-container">
                <div class="bg-slide active" style="background-image: url('{{ asset('assets/img/bgwebcp1.jpg') }}');"></div>
                <div class="bg-slide next" style="background-image: url('{{ asset('assets/img/bgwebp2.jpg') }}');"></div>
            </div>

            <!-- Content -->
            <div class="container position-relative" style="z-index: 10;">
                <div class="row gy-4 align-items-center">
                    <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up">
                        <h1>PT. Hasta Panca Mandiri Utama Site KDA</h1>
                        <p data-aos="fade-up" data-aos-delay="100">Mitra terpercaya Anda dalam layanan penambangan sejak 2016.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Styles -->
        <style>
            .hero {
                position: relative;
                overflow: hidden;
                min-height: 100vh;
            }

            .bg-slider-container {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 1;
                overflow: hidden;
            }

            .bg-slide {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-size: cover;
                background-position: center;
                transition: transform 1s ease-in-out;
                z-index: 1;
            }

            .bg-slide.active {
                transform: translateX(0);
                z-index: 2;
            }

            .bg-slide.next {
                transform: translateX(100%);
                z-index: 1;
            }
       #tentangImageCarousel {
        height: 80%;
        border-radius: 10px;
        overflow: hidden;
        margin-left: 30%
    }

    #tentangImageCarousel .carousel-inner {
        height: 100%;
    }

    #tentangImageCarousel .carousel-item {
        height: 100%;
        transition: opacity 0.5s ease-in-out; /* Gunakan fade, bukan slide */
    }

    #tentangImageCarousel .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    /* Opsional: ganti animasi slide jadi fade (lebih halus, tidak melebar) */
    #tentangImageCarousel .carousel-item-next,
    #tentangImageCarousel .carousel-item-prev,
    #tentangImageCarousel .carousel-item.active {
        display: block;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
    }

    #tentangImageCarousel .carousel-item.active {
        opacity: 1;
        position: relative;
    }
        </style>

        <!-- Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const images = [
                    "{{ asset('assets/img/bgwebcp1.jpg') }}",
                    "{{ asset('assets/img/bgwebp2.jpg') }}",
                    "{{ asset('assets/img/bgwebcp3.jpg') }}",
                    "{{ asset('assets/img/backgroundweb1.jpg') }}",
                    "{{ asset('assets/img/cp-img.jpg') }}",
                ];

                let currentIndex = 0;
                const container = document.querySelector('.bg-slider-container');

                setInterval(() => {
                    const currentSlide = container.querySelector('.bg-slide.active');
                    const nextSlide = container.querySelector('.bg-slide.next');

                    const nextIndex = (currentIndex + 1) % images.length;
                    nextSlide.style.backgroundImage = `url(${images[nextIndex]})`;

                    // Animate both slides simultaneously
                    currentSlide.style.transform = 'translateX(-100%)';
                    nextSlide.style.transform = 'translateX(0)';

                    setTimeout(() => {
                        // After animation, reset positions
                        currentSlide.classList.remove('active');
                        currentSlide.classList.add('next');
                        currentSlide.style.transform = 'translateX(100%)';

                        nextSlide.classList.remove('next');
                        nextSlide.classList.add('active');

                        currentIndex = nextIndex;
                    }, 1000);
                }, 5000); // Adjust the interval to control the speed of the transition
            });
        </script>





            <!-- Tentang Perusahaan Section -->
            <!-- Tentang Perusahaan Section (Only Image Carousel) -->
            <section id="tentang" class="about section">
                <div class="container" data-aos="fade-up">
                    <div class="row gx-0">
                        <!-- Konten Teks Statis -->
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

                        <!-- Gambar Carousel -->
                        <div class="col-lg-6 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
                            <div id="tentangImageCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="{{ asset('assets/img/ttgpr1.jpg') }}" class="d-block w-100 img-fluid"
                                            alt="Gambar 1">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="{{ asset('assets/img/ttgpr2.jpg') }}" class="d-block w-100 img-fluid"
                                            alt="Gambar 2">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="{{ asset('assets/img/ttgpr3.jpg') }}" class="d-block w-100 img-fluid"
                                            alt="Gambar 3">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="{{ asset('assets/img/ttgpr4.jpg') }}" class="d-block w-100 img-fluid" alt="Gambar 4">
                                    </div>
                                </div>

                                <!-- Kontrol Navigasi -->
                                <button class="carousel-control-prev" type="button" data-bs-target="#tentangImageCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#tentangImageCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <style>
                .section-full-bg {
                    width: 100vw;
                    background-image: url('{{ asset('assets/img/bg1.jpg') }}');
                    background-size: cover;
                    background-position: center;
                    background-repeat: no-repeat;
                    padding: 2% 0%;
                    position: relative;
                    margin-bottom: 3%;
                    margin-left: calc(-50vw + 50%);
                }

                .section-full-bg::before {
                    content: '';
                    position: absolute;
                    inset: 0;
                    background-color: rgba(0, 0, 0, 0.2);
                    /* Optional overlay */
                    z-index: 1;
                }

                .section-full-bg .section-title {
                    position: relative;
                    z-index: 2;
                }
            </style>


            <!-- Achievements Section -->
            <section id="achievements" class="achievements section">
                <div class="section-full-bg">
                    <div class="container" data-aos="fade-up">
                        <div class="section-title text-center text-white">
                            <h2 style="color: #fff;">Achievements</h2>
                            <p style="color: #fff;">Pencapaian dan Prestasi Kami</p>
                        </div>
                    </div>
                </div>
                    <div class="container mt-4" data-aos="fade-up">
                    <style>
                        #achievementCarousel .carousel-control-prev-icon,
                        #achievementCarousel .carousel-control-next-icon {
                            background-color: black;
                        }

                        #achievementCarousel .carousel-indicators [data-bs-target] {
                            background-color: black;
                        }

                        .card {
                            border: none; /* Remove border line from cards */
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
                </div>
            </section>

            <!-- Layanan Section -->
            <section id="departemen" class="values section">
                <div class="container">
                    <!-- Full Width Section Background -->
                    <div class="section-full-bg">
                        <div class="section-title text-center text-white" data-aos="fade-up">
                            <h2 style="color : #ffff" >Section Kami</h2>
                            <p style="color : #ffff">Section yang mendukung operasi penambangan kami</p>
                        </div>
                    </div>


                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 gy-4"> <!-- Tambahkan gy-4 untuk margin antar card -->
                        <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                            <div class="card card-small">
                                <img src="{{ asset('assets/img/sdt.jpg') }}" alt="Gear Plant">
                                <h3>Section SDT</h3>
                                <p>Bertanggung jawab atas survei, pemetaan, dan penyediaan data teknis untuk mendukung kegiatan operasional tambang.</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="card card-small">
                                <img src="{{ asset('assets/img/dt1.png') }}" alt="dt">
                                <h3>Section DT</h3>
                                <p>Bertanggung jawab atas pemeliharaan peralatan berat.</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                            <div class="card card-small">
                                <img src="{{ asset('assets/img/a2b.png') }}" alt="cr">
                                <h3>Section A2B</h3>
                                <p>Fokus pada dukungan operasional kegiatan penambangan.</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                            <div class="card card-small">
                                <img src="{{ asset('assets/img/wt.png') }}" alt="sp">
                                <h3>Section Support</h3>
                                <p>Memberikan layanan dukungan penting untuk operasi penambangan.</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                            <div class="card card-small">
                                <img src="{{ asset('assets/img/wp.png') }}" alt="hr">
                                <h3>Section WP</h3>
                                <p>Menangani pemeliharaan fasilitas bengkel.</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                            <div class="card card-small">
                                <img src="{{ asset('assets/img/fabrikasi.jpg') }}" alt="hrp">
                                <h3>Section Fabrikasi</h3>
                                <p>Bertanggung jawab atas fabrikasi dan perbaikan peralatan penambangan.</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="700">
                            <div class="card card-small">
                                <img src="{{ asset('assets/img/comex.png') }}" alt="box">
                                <h3>Section Comex</h3>
                                <p>Fokus pada kegiatan komersial dan ekspor.</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="800">
                            <div class="card card-small">
                                <img src="{{ asset('assets/img/tyre.png') }}" alt="tr">
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

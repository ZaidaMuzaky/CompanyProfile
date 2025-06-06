@extends('layouts.app')

@section('title', 'HPMU Poster')

@section('content')
    <style>
        .hpu-hero {
            position: relative;
            width: 100%;
            height: 350px;
            background: url("{{ asset('assets/img/cp-img.jpg') }}") center 75%/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 32px;
        }

        .hpu-hero-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.2);
        }

        .hpu-hero-text {
            position: absolute;
            left: 24%;
            /* geser sedikit ke kanan dari sisi kiri */
            bottom: 10%;
            /* geser ke bawah dari bawah hero */
            color: #fff;
            font-size: 2.6rem;
            font-weight: 700;
            z-index: 1;
            text-shadow: 1px 2px 8px rgba(0, 0, 0, 0.20);
        }





        .hpu-intro {
            max-width: 800px;
            margin: 0 auto 40px auto;
            text-align: justify;
            font-size: 1.2rem;
            color: #333;
        }

        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
            gap: 40px 32px;
            max-width: 1020px;
            margin: 0 auto 60px auto;
        }

        .project-card {
            background: #fafafa;
            border-radius: 9px;
            box-shadow: 0 2px 12px rgba(44, 50, 56, 0.05);
            overflow: hidden;
            text-align: center;
            text-decoration: none;
            color: inherit;
            transition: box-shadow .18s;
            width: 100%;
            max-width: 350px;
            /* Perbesar ukuran card */
            margin: 0 auto;
            /* Tambahkan margin untuk sentralisasi */
        }

        .project-card:hover {
            box-shadow: 0 4px 24px rgba(44, 50, 56, 0.12);
        }

        .project-img {
            width: 100%;
            height: 250px;
            /* Perbesar tinggi gambar */
            object-fit: cover;
            background: #eee;
            display: block;
        }

        .project-title {
            margin: 16px 0 12px 0;
            font-size: 1.1rem;
            font-weight: 500;
            color: #222;
        }

        @media (max-width: 600px) {
            .hpu-hero {
                height: 180px;
                margin-bottom: 18px;
            }

            .hpu-hero-text {
                font-size: 1.2rem;
                margin-left: 18px;
            }

            .projects-grid {
                gap: 18px;
            }
        }
    </style>

    <div class="hpu-hero">
        <div class="hpu-hero-overlay"></div>
        <div class="hpu-hero-text">
            Poster
        </div>
    </div>

    <div class="hpu-intro">

    </div>

    <div class="projects-grid">
        @foreach ($communities as $community)
            <div class="project-card">
                <img class="project-img" src="{{ asset('storage/' . $community->image) }}" alt="{{ $community->title }}">
                <div class="project-title">{{ $community->title }}</div>
            </div>
        @endforeach
    </div>
    <!-- Modal untuk image preview -->
    <div id="imageModal"
        style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.8); align-items:center; justify-content:center;">
        <span id="closeModal"
            style="position:absolute; top:20px; right:30px; color:white; font-size:30px; font-weight:bold; cursor:pointer;">&times;</span>
        <img id="modalImage" style="max-width:90%; max-height:90%; border:5px solid white; border-radius:8px;" />
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            const closeModal = document.getElementById('closeModal');

            document.querySelectorAll('.project-img').forEach(function(img) {
                img.addEventListener('click', function() {
                    modal.style.display = 'flex';
                    modalImg.src = this.src;
                });
            });

            closeModal.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            // Optional: tutup modal saat klik di luar gambar
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>

@endsection

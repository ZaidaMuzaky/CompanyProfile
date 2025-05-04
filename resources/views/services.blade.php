@extends('layouts.app')

@section('title', 'Services')

@section('content')

    <style>
        /* Import Roboto if needed */
        @import url('https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap');

        body {
            /* Don't override whole body on this page if you already have styles globally. */
            font-family: 'Roboto', Arial, Helvetica, sans-serif;
            background: #fbfbfb;
            color: #444;
        }

        .services-hpu-hero {
            position: relative;
            width: 100%;
            height: 370px;
            background: #e5e5e5;
            margin-bottom: 40px;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
        }

        @media (max-width:900px) {
            .services-hpu-hero {
                height: 220px;
            }
        }

        .services-hpu-hero-img-wrapper {
            width: 100%;
            height: 100%;
            overflow: hidden;
            position: absolute;
            left: 0;
            top: 0;
            z-index: 1;
        }

        .services-hpu-hero-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .services-hpu-hero-title {
            position: relative;
            z-index: 2;
            padding-left: 362px;
            padding-bottom: 30px;
            color: #fff;
            font-size: 2.75rem;
            font-weight: 700;
            letter-spacing: -1.5px;
            text-shadow: 0 3px 18px #0009;
        }

        @media (max-width:900px) {
            .services-hpu-hero-title {
                font-size: 1.65rem;
                padding-left: 18px;
            }
        }

        /* Intro Paragraf */
        .services-hpu-intro {
            font-size: 1.09rem;
            color: #444;
            text-align: justify;
            margin: 0 auto 36px auto;
            max-width: 710px;
            padding: 0 15px;
            line-height: 1.6;
        }

        /* Grid 4 Service */
        .services-hpu-grid {
            max-width: 1190px;
            margin: 0 auto 47px auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            padding: 0 16px;
        }

        @media (max-width:1050px) {
            .services-hpu-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 19px;
            }
        }

        @media (max-width:660px) {
            .services-hpu-grid {
                grid-template-columns: 1fr;
                gap: 17px;
            }
        }

        .services-hpu-card {
            background: #fff;
            border: 1px solid #e3e3e3;
            border-radius: 18px;
            box-shadow: 0 2px 10px #0001;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 26px 10px 22px 10px;
            transition: box-shadow .22s;
            min-height: 162px;
        }

        .services-hpu-card:hover {
            box-shadow: 0 8px 36px #ba3d2c22;
        }

        .services-hpu-card img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-bottom: 14px;
        }

        .services-hpu-card span {
            font-weight: 500;
            color: #ba3d2c;
            font-size: 1.09rem;
            text-align: center;
            line-height: 1.4;
        }

        .services-hpu-details {
            max-width: 1190px;
            margin: 0 auto 56px auto;
            display: flex;
            flex-direction: column;
            gap: 54px;
            padding: 0 16px;
        }

        .services-hpu-row {
            display: flex;
            gap: 46px;
            align-items: center;
            background: #fff;
            border-radius: 18px;
            padding: 25px 10px 25px 34px;
            box-shadow: 0 1px 10px #0001;
            justify-content: space-between;
        }

        .services-hpu-row.reverse {
            flex-direction: row-reverse;
        }

        @media (max-width: 900px) {

            .services-hpu-row,
            .services-hpu-row.reverse {
                flex-direction: column-reverse;
                align-items: flex-start;
                padding: 19px 8px;
                gap: 22px;
            }
        }

        .services-hpu-row img {
            width: 100%;
            max-width: 500px; /* Adjusted size */
            height: auto;
            border-radius: 13px;
            object-fit: contain;
            box-shadow: 0 0px 10px #0001;
            background: #f8f8f8;
        }

        .services-hpu-row-content {
            flex: 1;
            min-width: 175px;
        }

        .services-hpu-row-content h2 {
            color: #353633;
            font-size: 1.22rem;
            font-weight: bold;
            margin-bottom: 7px;
            letter-spacing: -0.5px;
            font-family: 'Roboto', sans-serif;
        }

        .services-hpu-row-content p {
            color: #444;
            font-size: 1rem;
            margin-bottom: 9px;
            line-height: 1.5;
        }

        .services-hpu-row-content ul {
            padding-left: 0;
            margin: 0;
            list-style: none;
        }

        .services-hpu-row-content li {
            margin-bottom: 2px;
            color: #ba3d2c;
            display: flex;
            align-items: center;
            font-size: 1rem;
            font-weight: 400;
        }

        .services-hpu-row-content li span {
            font-style: normal;
            margin-right: 12px;
            font-size: 1.13em;
            font-weight: bold;
        }
    </style>

    <!-- HERO SECTION -->
    <div class="services-hpu-hero">
        <div class="services-hpu-hero-img-wrapper">
            <img src="{{ asset('assets/img/Our-Services.jpg') }}" alt="Hero Banner" loading="lazy"
                class="services-hpu-hero-img">
        </div>
        <div class="services-hpu-hero-title"><b>Our Services</b></div>
    </div>

    <!-- INTRO PARAGRAPH -->
    <div class="services-hpu-intro">
        To conduct overall green mining process based on customer-centric solution through Excellent Engineering Practices
        enabled with competent engineer &amp; relevant technology adoption such as <b>Mine Planning &amp; Design, Mining
            Development, Mining Operation</b> and <b>Customer Solution Management</b>
    </div>

    <!-- SERVICES GRID/CARDS -->
    <div class="services-hpu-grid">
        <div class="services-hpu-card">
            <img src="https://hpu-mining.com/wp-content/uploads/2019/12/Mine-planning-Design-1.png"
                alt="Mine Planning & Design">
            <span><b>Mine Planning &amp; Design</b></span>
        </div>
        <div class="services-hpu-card">
            <img src="https://hpu-mining.com/wp-content/uploads/2019/12/Mining-development.png" alt="Mining Development">
            <span><b>Mining Development</b></span>
        </div>
        <div class="services-hpu-card">
            <img src="https://hpu-mining.com/wp-content/uploads/2019/12/Mining-operation-1.png" alt="Mining Operation">
            <span><b>Mining Operation</b></span>
        </div>
        <div class="services-hpu-card">
            <img src="https://hpu-mining.com/wp-content/uploads/2019/12/customer-solution-management.png"
                alt="Customer Solution Management">
            <span><b>Customer Solution Management</b></span>
        </div>
    </div>

    <!-- SERVICES DETAILS SECTION -->
    <div class="services-hpu-details">

        <!-- Mine Planning & Design -->
        <div class="services-hpu-row">
            <div class="services-hpu-row-content">
                <h2><b>Mine Planning &amp; Design</b></h2>
                <p>
                    HPU's experienced professional engineers and mining geologists provide insight on key aspects of
                    preliminary mine planning including the production schedule, equipment settings, mine designs as well as
                    mining method.
                </p>
                <ul>
                    <li><span>&rsaquo;</span> <b>Planning &amp; Design</b></li>
                    <li><span>&rsaquo;</span> <b>Mine Surveying</b></li>
                </ul>
            </div>
            <img src="{{ asset('assets/img/services1.jpg') }}"
                alt="Mine Planning & Design">
        </div>

        <!-- Mining Development -->
        <div class="services-hpu-row reverse">
            <div class="services-hpu-row-content">
                <h2><b>Mining Development</b></h2>
                <p>
                    HPU's experienced professional engineers provide the process of constructing a mining facility and the
                    infrastructure to support the facility, Mining development may involve many activities such as:
                    Infrastructure Development, Land Clearing, Top Soil Removal, as well as Road Construction.
                </p>
                <ul>
                    <li><span>&rsaquo;</span> <b>Infrastructure Development</b></li>
                    <li><span>&rsaquo;</span> <b>Land Clearing</b></li>
                    <li><span>&rsaquo;</span> <b>Top Soil Removal</b></li>
                    <li><span>&rsaquo;</span> <b>Road Construction</b></li>
                </ul>
            </div>
            <img src={{ asset('assets/img/services2.jpg') }} alt="Mining Development">
        </div>

        <!-- Mining Operation -->
        <div class="services-hpu-row">
            <div class="services-hpu-row-content">
                <h2><b>Mining Operation</b></h2>
                <p>
                    HPU's provide the process of managing many immediate and long-term activities in and around a mine site
                    in order to facilitate the production of coal. A mine operation has main activities: Drill &amp; Blast,
                    OB Removal / Earth moving, Coal Hauling, as well as mine dewatering and mining reclamation.
                </p>
                <ul>
                    <li><span>&rsaquo;</span> <b>Drill &amp; Blast</b></li>
                    <li><span>&rsaquo;</span> <b>OB Temoval/Earth Moving</b></li>
                    <li><span>&rsaquo;</span> <b>Coal Hauling</b></li>
                    <li><span>&rsaquo;</span> <b>Mine Dewatering</b></li>
                    <li><span>&rsaquo;</span> <b>Mining Reclamation</b></li>
                </ul>
            </div>
            <img src={{ asset('assets/img/services3.jpg') }} alt="Mining Operation">
        </div>

        <!-- Customer Solution Management -->
        <div class="services-hpu-row reverse">
            <div class="services-hpu-row-content">
                <h2><b>Customer Solution Management</b></h2>
                <p>
                    HPU's experienced professional business development approach to managing a customer solution with
                    current and potential customers. It uses data analysis to improve business relationships with customers,
                    specifically focusing on Exploration &amp; Mine pre-valuation and Operation HSE Management.
                </p>
                <ul>
                    <li><span>&rsaquo;</span> <b>Exploration &amp; Mine Pre-Valuation</b></li>
                    <li><span>&rsaquo;</span> <b>Operation HSE Management</b></li>
                </ul>
            </div>
            <img src="{{ asset('assets/img/services4.jpg') }}" alt="services4.jpg">

        </div>
    </div>

@endsection
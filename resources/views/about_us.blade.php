@extends('layouts.app')

@section('title', 'About Us')

@section('content')
    <style>
        :root {
            --hpu-orange: #ae9260;
            --hpu-hover-orange: #ff6e23;
            --hpu-dark: #23262b;
            --hpu-gray: #484d59;
            --hpu-lightgray: #ededed;
            --hpu-bg: #fbfbfb;
        }

        .about-hero {
            position: relative;
            height: 378px;
            display: flex;
            align-items: center;
            background: url('{{ asset('assets/img/about-us.jpg') }}') center center/cover no-repeat;
        }

        .about-hero-title {
            color: #fff;
            font-size: 3.5rem;
            font-weight: 700;
            font-family: 'Roboto', Arial, sans-serif;
            letter-spacing: 1px;
            text-shadow: 0 4px 40px rgba(0, 0, 0, 0.19);
            margin-left: 362px;
            margin-bottom: -250px;
            text-align: left;
            line-height: 1.05;
        }

        .about-content-container {
            display: flex;
            max-width: 1180px;
            margin: 28px auto 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 38px 0 rgba(43, 46, 53, 0.12);
            font-family: 'Roboto', Arial, sans-serif;
            padding: 54px 0 64px 0;
            position: relative;
            z-index: 2;
        }

        .about-sidebar {
            width: 268px;
            padding-left: 72px;
            padding-right: 6px;
            margin-right: 5px;
            border-right: 1px solid var(--hpu-lightgray);
        }

        .about-sidebar-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .about-sidebar-list li {
            font-size: 1.14rem;
            font-weight: 700;
            color: var(--hpu-dark);
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 0;
            border-bottom: 1px solid var(--hpu-lightgray);
            padding: 25px 0 13px 0;
            cursor: pointer;
            transition: color 0.12s, border 0.12s;
            background: none;
            outline: none;
        }

        .about-sidebar-list li:last-child {
            border-bottom: none;
        }

        .about-sidebar-list li.active {
            color: var(--hpu-hover-orange);
            border-bottom: 2.5px solid var(--hpu-hover-orange);
            background: none;
        }

        .about-sidebar-list li:hover:not(.active) {
            color: var(--hpu-orange);
        }

        .about-main-content {
            flex: 1;
            min-width: 0;
            padding: 0 48px;
            display: flex;
            flex-direction: column;
        }

        .about-section {
            display: none;
            animation: none;
        }

        .about-section.active {
            display: block;
            animation: fadeIn 0.30s;
        }

        .about-section-title {
            color: var(--hpu-hover-orange);
            font-size: 1.26rem;
            font-weight: 700;
            margin-top: 8px;
            margin-bottom: 20px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-family: 'Roboto', Arial, sans-serif;
        }

        .about-text {
            color: var(--hpu-gray);
            font-size: 1.09rem;
            line-height: 1.75;
            letter-spacing: 0.02em;
            font-family: 'Roboto', Arial, sans-serif;
            max-width: 800px;
            margin-bottom: 28px;
            text-align: left;
            /* Added to align text to the left */
        }

        .about-subheading {
            color: var(--hpu-dark);
            font-size: 1.2rem;
            font-weight: 700;
            margin-top: 24px;
            margin-bottom: 10px;
            font-family: 'Roboto', Arial, sans-serif;
        }

        .about-list {
            padding-left: 0;
            margin-top: 20px;
        }

        .about-list li {
            color: var(--hpu-gray);
            font-size: 1.09rem;
            line-height: 1.75;
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
        }

        .about-list li i {
            color: var(--hpu-hover-orange);
            margin-right: 10px;
            margin-top: 7px;
        }

        .core-values {
            display: flex;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .core-value-item {
            flex: 0 0 50%;
            padding: 15px;
            text-align: center;
        }

        .core-value-item img {
            width: 98px;
            height: 98px;
            margin-bottom: 15px;
        }

        .core-value-item h3 {
            color: var(--hpu-dark);
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .core-value-item p {
            color: var(--hpu-gray);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Award & Recognition styles */
        .award-toggle {
            margin-top: 20px;
        }

        .award-toggle-item {
            border: 1px solid var(--hpu-lightgray);
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .award-toggle-title {
            font-size: 1.1rem;
            font-weight: 700;
            padding: 15px 25px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f9f9f9;
        }

        .award-toggle-title i {
            color: var(--hpu-hover-orange);
            font-size: 1rem;
            transition: transform 0.3s;
        }

        .award-toggle-title.active i {
            transform: rotate(180deg);
        }

        .award-toggle-content {
            display: none;
            padding: 20px 25px;
            border-top: 1px solid var(--hpu-lightgray);
        }

        .award-toggle-content.active {
            display: block;
        }

        .award-item {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .award-image {
            flex: 0 0 33%;
            padding-right: 20px;
        }

        .award-image img {
            max-width: 100%;
            height: auto;
            border: 1px solid #eee;
        }

        .award-details {
            flex: 0 0 67%;
        }

        .award-details h3 {
            color: var(--hpu-dark);
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .award-details p {
            color: var(--hpu-gray);
            margin-bottom: 5px;
            line-height: 1.6;
        }

        @media (max-width: 1024px) {
            .about-content-container {
                flex-direction: column;
                margin-top: -40px;
                padding: 36px 0 32px 0;
            }

            .about-sidebar,
            .about-main-content {
                width: 100%;
                padding-left: 28px;
                padding-right: 28px;
            }

            .about-sidebar {
                border-right: none;
                border-bottom: 1px solid var(--hpu-lightgray);
                margin-bottom: 20px;
                padding-right: 0;
                padding-left: 0;
            }

            .core-value-item {
                flex: 0 0 100%;
            }

            .award-image {
                flex: 0 0 100%;
                margin-bottom: 20px;
                padding-right: 0;
            }

            .award-details {
                flex: 0 0 100%;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <div class="about-hero">
        <div class="about-hero-title">
            About Us
        </div>
    </div>

    <div class="about-content-container">
        <aside class="about-sidebar">
            <ul class="about-sidebar-list" id="aboutSidebar">
                <li class="active" data-section="who-we-are">Who We Are</li>
                <li data-section="hpu-way">HPU Way</li>
                <li data-section="our-strength">Our Strength</li>
                <li data-section="our-system">Our System</li>
                <li data-section="award-recognition">Award & Recognition</li>
            </ul>
        </aside>
        <main class="about-main-content">
            <!-- Who We Are Section - from your provided content -->
            <div class="about-section active" id="section-who-we-are">
                <div class="about-text">
                    Harmoni Panca Utama, PT. (HPU), is a private company established on the 25th of January 2011 which
                    focuses only on the mining service.<br><br>
                    The management is confident that it will grow and prosper alongside the development of mining industry
                    in Indonesia with the strength of its experienced personnel with worldwide accepted quality standard
                    competencies in the mining services industry.
                </div>
            </div>

            <!-- HPU Way Section - from your provided content -->
            <div class="about-section" id="section-hpu-way">

                <div class="about-section-title">Philosophy</div>
                <div class="about-text">
                    Giving Added Value for Stakeholders Wherever, Whenever, Forever.
                </div>

                <div class="about-section-title">Vision</div>
                <div class="about-text">
                    To Be The First Class Total Mining Services Solution
                </div>

                <div class="about-section-title">Mission</div>
                <div class="about-text">
                    To Provide Reliable Mining Services Through HSE Excellence, Operational Excellence<br>
                    People Excellence<br>
                    And Proper Community Development Implementation
                </div>

                <div class="about-section-title">5 AS - Core Values</div>
                <div class="core-values">
                    <div class="core-value-item">
                        <img src="https://hpu-mining.com/wp-content/uploads/2019/12/Integrity.png" alt="Integrity">
                        <h3>Integrity (IntegritAS)</h3>
                        <p>Consistency between behavior and statement from the bottom of people's heart.</p>
                    </div>
                    <div class="core-value-item">
                        <img src="https://hpu-mining.com/wp-content/uploads/2019/12/Work-hard.png" alt="Work Hard">
                        <h3>Work Hard (Kerja KerAS)</h3>
                        <p>Achieving goals and objectives will not be happening without the act of working hard. It is about
                            driving ourselves to leap over and deal with any challenges in order to reach the expected
                            targets.</p>
                    </div>
                    <div class="core-value-item">
                        <img src="https://hpu-mining.com/wp-content/uploads/2019/12/Work-smart.png" alt="Work Smart">
                        <h3>Work Smart (Kerja CerdAS)</h3>
                        <p>Soaring behavior, pertinent knowledge and skill is greatly needed to support the achievement of
                            the company declared mission and vision.</p>
                    </div>
                    <div class="core-value-item">
                        <img src="https://hpu-mining.com/wp-content/uploads/2019/12/work-thorough.png" alt="Work Thorough">
                        <h3>Work Thorough (Kerja TuntAS)</h3>
                        <p>The company shall not tolerate inefficiency and thus work must be completed thoroughly.</p>
                    </div>
                    <div class="core-value-item">
                        <img src="https://hpu-mining.com/wp-content/uploads/2019/12/Work-sincerely.png"
                            alt="Work Sincerely">
                        <h3>Work Sincerely (Kerja IkhlAS)</h3>
                        <p>Sincerely is highly appreciated to have personal gratefulness of God Almighty which shall then
                            fulfill humans spiritual needs.</p>
                    </div>
                </div>
            </div>

            <!-- Our Strength Section - from your provided content -->
            <div class="about-section" id="section-our-strength">
                <div class="about-section-title">Our Strength</div>
                <div class="about-text">
                    HPU continuously focuses on building its distinctive customer value proposition that is believed to be
                    the company's strong competitive advantage to strengthen its reputation in the industry. The company
                    uses several key success factors in achieving these goals.
                </div>

                <ul class="about-list">
                    <li><i class="fas fa-chevron-circle-right"></i> HSE as main priority</li>
                    <li><i class="fas fa-chevron-circle-right"></i> Customer strategic partnership</li>
                    <li><i class="fas fa-chevron-circle-right"></i> Good Mining Practices & Operational Excellence Savvy
                    </li>
                    <li><i class="fas fa-chevron-circle-right"></i> Strong Social Community Relation</li>
                    <li><i class="fas fa-chevron-circle-right"></i> Good corporate governance</li>
                </ul>
            </div>

            <!-- Our System Section - from your provided content -->
            <div class="about-section" id="section-our-system">
                <div class="about-section-title">Our System</div>
                <div class="about-subheading">Panca System Galaxy</div>
                <div class="system-diagram">
                    <img src="{{ asset('assets/img/galaxy.png') }}"
                        style="width: 100%; max-width: 800px; margin-top: 20px;">
                </div>
            </div>

            <!-- Award & Recognition Section - from your provided content -->
            <div class="about-section" id="section-award-recognition">
                <div class="about-section-title">Award & Recognition</div>

                <div class="award-toggle">
                    <!-- 2024 Awards -->
                    <div class="award-toggle-item">
                        <div class="award-toggle-title" data-year="2024">
                            <span>2024</span>
                            <i class="fas fa-chevron-circle-down"></i>
                        </div>
                        <div class="award-toggle-content" id="award-2024">
                            <div class="award-item">
                                <div class="award-image">
                                    <img src="https://hpu-mining.com/wp-content/uploads/2024/09/HPU-DTA_PRATAMA_page-0001.jpg"
                                        alt="Penghargaan Pratama 2024">
                                </div>
                                <div class="award-details">
                                    <h3>Penghargaan Pratama</h3>
                                    <p>KEMENTRIAN ENERGI DAN SUMBER DAYA MINERAL REPUBLIK INDONESIA 2024</p>
                                    <p>PT. Harmoni Panca Utama<br>Site PT Mahakam Sumber Jaya</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2023 Awards -->
                    <div class="award-toggle-item">
                        <div class="award-toggle-title" data-year="2023">
                            <span>2023</span>
                            <i class="fas fa-chevron-circle-down"></i>
                        </div>
                        <div class="award-toggle-content" id="award-2023">
                            <div class="award-item">
                                <div class="award-image">
                                    <img src="https://hpu-mining.com/wp-content/uploads/2023/10/Piagam-UTAMA-HPU-scaled.jpg"
                                        alt="Penghargaan Utama 2023">
                                </div>
                                <div class="award-details">
                                    <h3>Penghargaan Utama</h3>
                                    <p>KEMENTRIAN ENERGI DAN SUMBER DAYA MINERAL REPUBLIK INDONESIA 2023</p>
                                    <p>PT. Harmoni Panca Utama<br>Head Office Jakarta</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2022 Awards -->
                    <div class="award-toggle-item">
                        <div class="award-toggle-title" data-year="2022">
                            <span>2022</span>
                            <i class="fas fa-chevron-circle-down"></i>
                        </div>
                        <div class="award-toggle-content" id="award-2022">
                            <div class="award-item">
                                <div class="award-image">
                                    <img src="https://hpu-mining.com/wp-content/uploads/2023/02/IMS-Awards-2022_1.jpeg"
                                        alt="Penghargaan Perak 2022">
                                </div>
                                <div class="award-details">
                                    <h3>Penghargaan Perak</h3>
                                    <p>From Indonesia Mining Service Awards 2022</p>
                                    <p>PT. Harmoni Panca Utama<br>Head Office Jakarta</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2020 Awards -->
                    <div class="award-toggle-item">
                        <div class="award-toggle-title" data-year="2020">
                            <span>2020</span>
                            <i class="fas fa-chevron-circle-down"></i>
                        </div>
                        <div class="award-toggle-content" id="award-2020">
                            <div class="award-item">
                                <div class="award-image">
                                    <img src="https://hpu-mining.com/wp-content/uploads/2020/07/WISCA-Award-2020_HPU-scaled.jpg"
                                        alt="WISCA Award 2020">
                                </div>
                                <div class="award-details">
                                    <h3>WISCA AWARD 2020</h3>
                                    <p>FROM WORLD SAFETY ORGANIZATION (WSO)</p>
                                    <p>PT. Harmoni Panca Utama<br>Head Office Jakarta</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2017 Awards -->
                    <div class="award-toggle-item">
                        <div class="award-toggle-title" data-year="2017">
                            <span>2017</span>
                            <i class="fas fa-chevron-circle-down"></i>
                        </div>
                        <div class="award-toggle-content" id="award-2017">
                            <div class="award-item">
                                <div class="award-image">
                                    <img src="https://hpu-mining.com/wp-content/uploads/2020/01/Certificate_UTAMA_HPU@2x-e1577938617456.png"
                                        alt="Penghargaan Utama 2017">
                                </div>
                                <div class="award-details">
                                    <h3>Penghargaan UTAMA</h3>
                                    <p>Pengelolaan Keselamatan Pertambangan Kontraktor Utama Jasa Pertambangan Mineral dan
                                        Batubara Periode Tahun 2016</p>
                                    <p>PT. Harmoni Panca Utama<br>Site PT Tanito Harum</p>
                                </div>
                            </div>
                            <div class="award-item">
                                <div class="award-image">
                                    <img src="https://hpu-mining.com/wp-content/uploads/2020/01/Certificate_ZERO_INCIDENT_PDU@2x-e1577947006752.png"
                                        alt="Penghargaan Kecelekaan Nihil 2017">
                                </div>
                                <div class="award-details">
                                    <h3>Penghargaan Kecelekaan Nihil</h3>
                                    <p>Kabupaten Kutai Kartanegara, Provinsi Kalimantan Timur 2017 Pencapaian 5.723.486 jam
                                        kerja tanpa kecelakaan kerja, terhitung sejak 01 Agustus 2013 s/d 31 Desember 2016
                                    </p>
                                    <p>PT. Harmoni Panca Utama<br>Site Pondok Labu</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2016 Awards -->
                    <div class="award-toggle-item">
                        <div class="award-toggle-title" data-year="2016">
                            <span>2016</span>
                            <i class="fas fa-chevron-circle-down"></i>
                        </div>
                        <div class="award-toggle-content" id="award-2016">
                            <div class="award-item">
                                <div class="award-image">
                                    <img src="https://hpu-mining.com/wp-content/uploads/2020/01/Certificate_ADITAMA_HPU.jpg"
                                        alt="Penghargaan Aditama 2016">
                                </div>
                                <div class="award-details">
                                    <h3>Penghargaan ADITAMA</h3>
                                    <p>Pengelolaan Keselamatan Pertambangan Kontraktor Utama Jasa Pertambangan Mineral dan
                                        Batubara Periode Tahun 2015</p>
                                    <p>PT. Harmoni Panca Utama<br>Site PT Tambang Damai</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- More years could be added in the same pattern -->
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Tab switching
            const sidebar = document.getElementById('aboutSidebar');
            const items = sidebar.querySelectorAll('li');
            const sections = document.querySelectorAll('.about-section');

            items.forEach(item => {
                item.addEventListener('click', function () {
                    items.forEach(i => i.classList.remove('active'));
                    sections.forEach(s => s.classList.remove('active'));
                    item.classList.add('active');
                    const secId = "section-" + item.getAttribute('data-section');
                    const section = document.getElementById(secId);
                    if (section) section.classList.add('active');
                });
            });

            // Award toggle functionality
            const awardToggleTitles = document.querySelectorAll('.award-toggle-title');

            awardToggleTitles.forEach(title => {
                title.addEventListener('click', function () {
                    const year = this.getAttribute('data-year');
                    const content = document.getElementById('award-' + year);

                    // Toggle active state for this item
                    this.classList.toggle('active');

                    // Hide all award content first
                    document.querySelectorAll('.award-toggle-content').forEach(c => {
                        if (c.id !== 'award-' + year) {
                            c.classList.remove('active');
                        }
                    });

                    // Remove active class from other titles
                    document.querySelectorAll('.award-toggle-title').forEach(t => {
                        if (t !== this) {
                            t.classList.remove('active');
                        }
                    });

                    // Toggle the selected content
                    content.classList.toggle('active');
                });
            });
        });
    </script>
@endsection
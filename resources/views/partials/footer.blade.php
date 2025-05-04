<footer id="footer" class="footer">
    <div class="container footer-top">
        <div class="row gy-4">
            <!-- About Us -->
            <div class="col-lg-3 col-md-6 footer-about">
                <h2>About Us</h2>
                <ul>
                    <li><i class="fas fa-angle-right"></i> <a href="{{ route('about-us') }}">Who We Are</a></li>
                    <li><i class="fas fa-angle-right"></i> <a href="{{ route('about-us') }}">HPU Way</a></li>
                    <li><i class="fas fa-angle-right"></i> <a href="{{ route('about-us') }}">Strength</a></li>
                    <li><i class="fas fa-angle-right"></i> <a href="{{ route('about-us') }}">Our System</a></li>
                    <li><i class="fas fa-angle-right"></i> <a href="{{ route('about-us') }}">Awards & Recognition</a></li>
                    <li><i class="fas fa-angle-right"></i> <a href="{{ route('career') }}">Career</a></li>
                    <li><i class="fas fa-angle-right"></i><a href="{{ route('newsvisit.index') }}">News</a></li>
                </ul>
            </div>

            <!-- Our Services -->
            <div class="col-lg-3 col-md-6 footer-services">
                <h2>Our Services</h2>
                <ul>
                    <li><i class="fas fa-angle-right"></i> <a href="{{ route('services') }}">Mine Planning & Design</a></li>
                    <li><i class="fas fa-angle-right"></i> <a href="{{ route('services') }}">Mining Development</a></li>
                    <li><i class="fas fa-angle-right"></i> <a href="{{ route('services') }}">Mining Operation</a></li>
                    <li><i class="fas fa-angle-right"></i> <a href="{{ route('services') }}">Customer Solution Management</a></li>
                </ul>
            </div>

            <!-- Living at HPU -->
            <div class="col-lg-3 col-md-6 footer-living">
                <h2>Living at HPU</h2>
                <ul>
                    <li><i class="fas fa-angle-right"></i> <a href="{{ route('people') }}">Our People</a></li>
                    <li><i class="fas fa-angle-right"></i> <a href="{{ route('community') }}">Our Community</a></li>
                </ul>
            </div>

            <!-- Contact Us -->
            <div class="col-lg-3 col-md-6 footer-contact">
                <h2>Contact Us</h2>
                <p>PT HARMONI PANCA UTAMA</p>
                <p>Gedung Menara Palma Lantai 11</p>
                <p>Jl. HR. Rasuna Said Kav. 6 Blok X-2</p>
                <p>Kel. Kuningan Timur, Kec Kuningan</p>
                <p>South Jakarta - 12950</p>
                <p><strong>Telp.:</strong> <a href="tel:622157955818">+62 21 57955818</a></p>
                <p><strong>Fax.:</strong> +62 21 57955819</p>
                <div class="social-links">
                    <a href="https://www.instagram.com/hpu_official/?hl=id" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-linkedin"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>
    <!-- Copyright Section -->
    <div class="w-100" style="
          background: url('{{ asset('assets/img/Footer.jpg') }}') no-repeat center center;
          background-size: cover;
        ">
        <!-- Boks container untuk teks agar tidak terlalu melebar -->
        <div class="container text-center py-5">
            <p class="mb-0 text-white">
                Copyright ©2025 PT Harmoni Panca Utama. All Rights Reserved
            </p>
        </div>
    </div>

<footer id="footer" class="footer">
    <div class="container footer-top">
        <div class="row gy-4">
            <!-- About Us -->
            <div class="col-lg-3 col-md-6 footer-about">
                <h2>About Us</h2>
                <ul>
                    <li><i class="fas fa-angle-right"></i><a href="{{ route('newsvisit.index') }}">News</a></li>
                </ul>
            </div>

            <!-- Our Services -->
            <div class="col-lg-3 col-md-6 footer-services">
            </div>

            <!-- Living at HPU -->
            <div class="col-lg-3 col-md-6 footer-living">
                <h2>Living at HPMU</h2>
                <ul>
                    <li><i class="fas fa-angle-right"></i> <a href="{{ route('people') }}">Our People</a></li>
                    <li><i class="fas fa-angle-right"></i> <a href="{{ route('community') }}">Our Community</a></li>
                </ul>
            </div>

            <!-- Contact Us -->
            <div class="col-lg-3 col-md-6 footer-contact">
                <h2>Contact Us</h2>
                <p>PT. Hasta Panca Mandiri Utama Site KDA</p>
                <p>Desa Karya Baru, Kec. Marau, Kab. Ketapang, Prov. Kalimantan Barat 78863</p>
                <p><strong>Telp.:</strong> <a href="tel:6282148000417"> +62 82148000417‬</a></p>
                <p><strong>Email :</strong> kda.ins.plt@hpmu-mining.com</p>
                <div class="social-links">
                    <a href="https://www.instagram.com/kda.plt_hpmu" target="_blank"><i class="fab fa-instagram"></i></a>
                    {{-- <a href="#" target="_blank"><i class="fab fa-linkedin"></i></a> --}}
                    {{-- <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a> --}}
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
                Copyright ©2025 PT Hasta Panca Mandiri Utama. All Rights Reserved
            </p>
        </div>
    </div>

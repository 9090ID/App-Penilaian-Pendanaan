<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Kemahasiswaan-Penilaian Kegiatan</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{ asset('12.png') }}" rel="icon">
    <link href="{{ asset('12.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('fr/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fr/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('fr/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('fr/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fr/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('fr/css/main.css') }}" rel="stylesheet">

    <!-- =======================================================
  * Template Name: Kelly
  * Template URL: https://bootstrapmade.com/kelly-free-bootstrap-cv-resume-html-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center light-background sticky-top">
        <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

            <a href="{{ route('index') }}" class="logo d-flex align-items-center me-auto me-xl-0">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="{{ asset('12.png') }}" alt="">
                <h1 class="sitename">Kemahasiswaan UNJA</h1>
            </a>

             <nav id="navmenu" class="navmenu">
               {{-- <ul>
                    {{-- <li><a href="index.html" class="active">Home</a></li>
            </li> --}}
                    {{-- <li><a href="{{ route('layanan') }}">Layanan</a></li> --}}
                    {{-- <li><a href="resume.html">Prestasi</a></li>
                    <li><a href="services.html">Beasiswa</a></li>
                    <li><a href="portfolio.html">Berkas</a></li> --}}
                    {{-- <li><a href="{{ route('login') }}">Login</a></li>
                </ul>--}}
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav> 

            <div class="header-social-links">
                <a href="{{ route('login') }}" ><i class="bi bi-lock"></i>Login</a>
                {{-- <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a> --}}
            </div>

        </div>
    </header>

@yield('content')


<footer id="footer" class="footer light-background py-3" style="font-size: 0.9rem;">
    <div class="container">
        <div class="copyright text-center">
            <p class="mb-1">© <span>Copyright</span> <strong class="px-1 sitename">KM</strong> All Rights Reserved</p>
            <small>Designed by Kemahasiswaan Universitas Jambi</small>
        </div>
    </div>
</footer>


    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('fr/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('fr/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('fr/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('fr/vendor/waypoints/noframework.waypoints.js') }}"></script>
    <script src="{{ asset('fr/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('fr/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('fr/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('fr/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('fr/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('fr/js/main.js') }}"></script>

</body>

</html>

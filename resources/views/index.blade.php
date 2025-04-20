@extends('welcome')
@section('content')
<section id="hero" class="hero section position-relative" style="min-height: 80vh; overflow: hidden;">

    <!-- Background Image -->
    <img src="{{ asset('13.jpg') }}" alt="" class="img-fluid w-100 h-100 object-cover position-absolute top-0 start-0" style="z-index: 1;">

    <!-- Black Transparent Overlay -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.5); z-index: 2;"></div>

    <!-- Hero Content -->
    <div class="container text-center position-relative d-flex flex-column justify-content-center align-items-center h-100 text-white" style="z-index: 3;" data-aos="zoom-out" data-aos-delay="100">
        <div style="background-color: rgba(0,0,0,0.4); padding: 30px 50px; border-radius: 10px;">
            <h1 class="display-4 fw-bold mb-3" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.8);">Selamat Datang</h1>
            <p class="lead mb-4" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.8);">
                Situs Penilaian Kegiatan Pendanaan Mahasiswa Universitas Jambi
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('login') }}" class="btn btn-danger btn-lg px-4 shadow">Login Reviewer</a>
                {{-- <a href="{{ route('daftar') }}" class="btn btn-danger btn-lg px-4 shadow">Daftar Kegiatan</a> --}}
            </div>
        </div>
    </div>

</section>



@endsection
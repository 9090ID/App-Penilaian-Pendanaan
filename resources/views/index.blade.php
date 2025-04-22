@extends('welcome')

@section('content')
    <!-- Hero Section -->
    <section id="hero" class="hero section position-relative" style="min-height: 80vh; overflow: hidden;">

        <!-- Background Image -->
        <img src="{{ asset('13.jpg') }}" alt="Background Image"
            class="img-fluid w-100 h-100 object-cover position-absolute top-0 start-0" style="z-index: 1;">

        <!-- Black Transparent Overlay -->
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.5); z-index: 2;">
        </div>

        <!-- Kegiatan Pendanaan Section (Cards Above Background) -->
        <section id="kegiatan" class="kegiatan section py-2 position-relative"
            style="z-index: 3; padding-top: 40px; background: none;">
            <div class="container">
                <h2 class="text-center mb-5" style="font-size: 2rem; color: #fff; font-weight: 600;">
                    Sistem Penilaian Kegiatan Pendanaan Mahasiswa tahun {{ date('Y') }}
                    <span class="badge bg-warning" style="color:orangered">UNIVERSITAS JAMBI</span>
                </h2>
                <!-- Card Group -->
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4 py-5">
                    @php
                        $cards = [
                            ['img' => 'ppkormawa.webp', 'title' => 'Program Penguatan Kapasitas Organisasi Kemahasiswaan', 'desc' => 'Program Penguatan Kapasitas Organisasi Kemahasiswaan'],
                            ['img' => 'p2mw1.png', 'title' => 'Program Pembinaan Mahasiswa Wirausaha', 'desc' => 'Program Pembinaan Mahasiswa Wirausaha'],
                            ['img' => 'pkm.png', 'title' => 'Program Kreativitas Mahasiswa', 'desc' => 'Program Kreativitas Mahasiswa'],
                            ['img' => 'proide.jpg', 'title' => 'Program Inovasi Desa', 'desc' => 'Program Inovasi Desa'],
                            ['img' => 'pmw.png', 'title' => 'Program Mahasiswa Wirausaha', 'desc' => 'Program Mahasiswa Wirausaha'],
                        ];
                    @endphp
                
                    @foreach ($cards as $card)
                        <div class="col">
                            <div class="card h-100 text-center border-0 shadow-lg rounded-4"
                                style="background-color: rgba(0, 0, 0, 0.6); padding-top: 60px; position: relative;">
                
                                <!-- Logo Bulat -->
                                <div class="position-absolute top-0 start-50 translate-middle"
                                    style="transform: translate(-50%, -50%); width: 100px; height: 100px; border-radius: 50%; overflow: hidden; background-color: white;">
                                    <img src="{{ asset($card['img']) }}" alt="{{ $card['title'] }}"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                
                                <div class="card-body mt-4 pt-2 d-flex flex-column">
                                    <h5 class="card-title text-white mt-3">{{ $card['title'] }}</h5>
                                    {{-- <p class="card-text text-white small">{{ $card['desc'] }}</p> --}}
                                    <div class="mt-auto">
                                        <a href="{{ route('login') }}" class="btn btn-danger btn-sm">Login Reviewer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>





            </div>
        </section>
    </section>
@endsection

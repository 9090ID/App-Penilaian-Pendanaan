@extends('welcome')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Nav Tabs -->
        <div class="col-md-3">
            <div class="nav flex-column nav-pills" id="v-tabs" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="tab-home" data-bs-toggle="pill" data-bs-target="#content-home" type="button" role="tab">Home</button>
                <button class="nav-link" id="tab-about" data-bs-toggle="pill" data-bs-target="#content-about" type="button" role="tab">About</button>
                <button class="nav-link" id="tab-contact" data-bs-toggle="pill" data-bs-target="#content-contact" type="button" role="tab">Contact</button>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="col-md-9">
            <div class="tab-content" id="v-tabs-content">
                <div class="tab-pane fade show active" id="content-home" role="tabpanel">
                    <h3>Home</h3>
                    <p>Selamat Datang di Website Kemahasiswaan UNJA!</p>
                </div>
                <div class="tab-pane fade" id="content-about" role="tabpanel">
                    <h3>About</h3>
                    <p>Ini adalah sistem informasi kemahasiswaan yang dikelola oleh Universitas Jambi.</p>
                </div>
                <div class="tab-pane fade" id="content-contact" role="tabpanel">
                    <h3>Contact</h3>
                    <p>Silakan hubungi kami di kemahasiswaan@unja.ac.id</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

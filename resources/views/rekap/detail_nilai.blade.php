@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 px-4">
            <div class="content">
                <h1>Rekap Nilai Proposal: {{ $proposal->judul_proposal }}</h1>

                <div class="card mb-4">
                    <div class="card-header"><strong>Informasi Proposal</strong></div>
                    <div class="card-body">
                        <p><strong>Nama Ketua:</strong> {{ $proposal->namaKetua }}</p>
                        <p><strong>NIM Ketua:</strong> {{ $proposal->nimKetua }}</p>
                        <p><strong>Dosen Pembimbing:</strong> {{ $dosenPembimbing }}</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><strong>Penilaian oleh Reviewer</strong></div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Reviewer</th>
                                    <th>Score</th>
                                    <th>Bobot</th>
                                    <th>Hasil Nilai (Score x Bobot)</th>
                                    <th>Komentar</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penilaian as $item)
                                    <tr>
                                        <td>{{ $item['reviewer_name'] }}</td>
                                        <td>{{ $item['score'] }}</td>
                                        <td>{{ $item['bobot'] }}</td>
                                        <td>{{ $item['hasil_nilai'] }}</td>
                                        <td>{{ $item['komentar'] }}</td>
                                        <td>{{ $item['catatan'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <h4><strong>Total Score Proposal: {{ $totalScore }}</strong></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

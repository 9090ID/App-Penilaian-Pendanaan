@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 px-1">
            <div class="content">
                <h1>Rekapitulasi Penilaian Proposal </h1>
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <select id="namaKegiatan" class="form-control">
                                    <option value="">Semua Kegiatan</option>
                                    @foreach($kegiatanList as $kegiatan)
                                        <option value="{{ $kegiatan->id }}">{{ $kegiatan->nama_kegiatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="kategoriKegiatan" class="form-control" disabled>
                                    <option value="">Semua Kategori</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="tahun" class="form-control">
                                    <option value="">Semua Tahun</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button id="btnExportExcel" class="btn btn-success mb-3">
                            Export Excel
                        </button>
                        <table id="rekapTable" class="display">
                            <thead>
                                <tr>
                                    <th>Judul Proposal</th>
                                    <th>Nama Ketua</th>
                                    <th>NIM Ketua</th>
                                    <th>Nama Kegiatan</th>
                                    <th>Kategori Kegiatan</th>
                                    <th>Skor</th>
                                    <th>Komentar dan Rekomendasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan diisi menggunakan AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
    $(document).ready(function () {
        var table = $('#rekapTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("getData") }}',
                type: 'GET',
                data: function (d) {
                    d.nama_kegiatan = $('#namaKegiatan').val();
                    d.kategori_kegiatan = $('#kategoriKegiatan').val();
                    d.tahun = $('#tahun').val();
                }
            },
            columns: [
                { data: 'judul_proposal', name: 'judul_proposal' },
                { data: 'namaKetua', name: 'namaKetua' },
                { data: 'nimKetua', name: 'nimKetua' },
                { data: 'nama_kegiatan', name: 'kegiatan.nama_kegiatan' },
                { data: 'kategori_kegiatan', name: 'kategori.nama_kategori' },
                { data: 'skor', name: 'skor' },
                { data: 'komentar_dan_rekomendasi', name: 'komentar_dan_rekomendasi' },
            ]
        });

        // Filter Nama Kegiatan
        $('#namaKegiatan').on('change', function () {
            var kegiatanId = $(this).val();

            // Ambil kategori berdasarkan kegiatan
            if (kegiatanId) {
                $('#kategoriKegiatan').prop('disabled', false);

                $.ajax({
                    url: '/get-kategori-kegiatan/' + kegiatanId,
                    type: 'GET',
                    success: function (response) {
                        $('#kategoriKegiatan').empty().append('<option value="">Semua Kategori</option>');

                        $.each(response.kategori, function (index, kategori) {
                            $('#kategoriKegiatan').append('<option value="' + kategori.id + '">' + kategori.nama_kategori + '</option>');
                        });
                    },
                    error: function () {
                        alert('Gagal mengambil kategori.');
                    }
                });
            } else {
                // Reset jika tidak dipilih
                $('#kategoriKegiatan').prop('disabled', true).empty().append('<option value="">Semua Kategori</option>');
            }

            table.ajax.reload(); // reload datatables
        });

        // Filter kategori & tahun
        $('#kategoriKegiatan, #tahun').on('change', function () {
            table.ajax.reload();
        });

        // Tombol Export Excel berdasarkan filter aktif
        $('#btnExportExcel').on('click', function () {
            const params = new URLSearchParams({
                kegiatan: $('#namaKegiatan').val(),
                kategori: $('#kategoriKegiatan').val(),
                tahun: $('#tahun').val()
            });
            window.location.href = `{{ route('rekap.exportExcel') }}?${params.toString()}`;
        });
    });
</script>
@endpush

@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 px-4">
            <div class="content">
                <h1>Tambah Proposal</h1>
                
                <form action="{{ route('proposal.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="kegiatan_id">Kegiatan</label>
                        <select class="form-control" id="kegiatan_id" name="kegiatan_id" required>
                            <option value="">Pilih Kegiatan</option>
                            @foreach($kegiatan as $kegiatanItem)
                                <option value="{{ $kegiatanItem->id }}">{{ $kegiatanItem->nama_kegiatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kategori">Kategori</label>
                        <select class="form-control" id="kategori" name="kategori_id" required>
                            <option value="">Pilih Kategori</option>
                            <!-- Kategori akan diisi melalui AJAX -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="namaKetua">Nama Ketua</label>
                        <input type="text" class="form-control" id="namaKetua" name="namaKetua" required>
                    </div>
                    <div class="form-group">
                        <label for="nimKetua">NIM Ketua</label>
                        <input type="text" class="form-control" id="nimKetua" name="nimKetua" required>
                    </div>
                    <div class="form-group">
                        <label for="nohpKetua">No HP Ketua</label>
                        <input type="text" class="form-control" id="nohpKetua" name="nohpKetua" required>
                    </div>
                    <div class="form-group">
                        <label for="prodiKetua">Program Studi Ketua</label>
                        <input type="text" class="form-control" id="prodiKetua" name="prodiKetua" required>
                    </div>
                    <div class="form-group">
                        <label for="fakultasKetua">Fakultas Ketua</label>
                        <input type="text" class="form-control" id="fakultasKetua" name="fakultasKetua" required>
                    </div>
                    <div class="form-group">
                        <label for="judul_proposal">Judul Proposal</label>
                        <input type="text" class="form-control" id="judul_proposal" name="judul_proposal" required>
                    </div>
                    <div class="form-group">
                        <label for="dosenPembimbing">Dosen Pembimbing</label>
                        <input type="text" class="form-control" id="dosenPembimbing" name="dosenPembimbing" required>
                    </div>
                    <div class="form-group">
                        <label for="nidnDosenPembimbing">NIDN Dosen Pembimbing</label>
                        <input type="text" class="form-control" id="nidnDosenPembimbing" name="nidnDosenPembimbing" required>
                    </div>
                    <div class="form-group">
                        <label for="anggota">Anggota (JSON format)</label>
                        <textarea class="form-control" id="anggota" name="anggota" required></textarea>
                        <small class="form-text text-muted">Masukkan anggota dalam format JSON. Contoh: {"Nama": "Rika", "NIM": "A1A1S1A1"}</small>
                    </div>
                    <div class="form-group">
                        <label for="linkproposal">Link Proposal</label>
                        <input type="url" class="form-control" id="linkproposal" name="linkproposal" required>
                    </div>
                    <div class="form-group">
                        <label for="reviewers">Pilih Reviewer</label>
                        <select class="form-control" id="reviewers" name="reviewers[]" multiple required>
                            @foreach($reviewers as $reviewer)
                                <option value="{{ $reviewer->id }}">{{ $reviewer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Proposal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#kegiatan_id').change(function() {
            var kegiatanId = $(this).val();
            if (kegiatanId) {
                $.ajax({
                    url: '/get-kategori/' + kegiatanId,
                    type: 'GET',
                    success: function(data) {
                        $('#kategori').empty();
                        $('#kategori').append('<option value="">Pilih Kategori</option>');
                        $.each(data, function(key, value) {
                            $('#kategori').append('<option value="' + value.id + '">' + value.nama_kategori + '</option>');
                        });
                    }
                });
            } else {
                $('#kategori').empty();
                $('#kategori').append('<option value="">Pilih Kategori</option>');
            }
        });
    });
</script>
@endsection
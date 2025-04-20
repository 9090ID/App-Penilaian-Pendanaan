@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 px-4">
            <div class="content">
                <h1>Edit Proposal</h1>
                
                <form action="{{ route('proposal.update', $proposal->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="kegiatan_id">Kegiatan</label>
                        <select class="form-control" id="kegiatan_id" name="kegiatan_id" required>
                            <option value="">Pilih Kegiatan</option>
                            @foreach($kegiatan as $kegiatanItem)
                                <option value="{{ $kegiatanItem->id }}" {{ $kegiatanItem->id == $proposal->kegiatan_id ? 'selected' : '' }}>
                                    {{ $kegiatanItem->nama_kegiatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kategori_id">Kategori</label>
                        <select class="form-control" id="kategori" name="kategori_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ $kategori->id == $proposal->kategori_id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="namaKetua">Nama Ketua</label>
                        <input type="text" class="form-control" id="namaKetua" name="namaKetua" value="{{ $proposal->namaKetua }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nimKetua">NIM Ketua</label>
                        <input type="text" class="form-control" id="nimKetua" name="nimKetua" value="{{ $proposal->nimKetua }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nohpKetua">No HP Ketua</label>
                        <input type="text" class="form-control" id="nohpKetua" name="nohpKetua" value="{{ $proposal->nohpKetua }}" required>
                    </div>
                    <div class="form-group">
                        <label for="prodiKetua">Program Studi Ketua</label>
                        <input type="text" class="form-control" id="prodiKetua" name="prodiKetua" value="{{ $proposal->prodiKetua }}" required>
                    </div>
                    <div class="form-group">
                        <label for="fakultasKetua">Fakultas Ketua</label>
                        <input type="text" class="form-control" id="fakultasKetua" name="fakultasKetua" value="{{ $proposal->fakultasKetua }}" required>
                    </div>
                    <div class="form-group">
                        <label for="judul_proposal">Judul Proposal</label>
                        <input type="text" class="form-control" id="judul_proposal" name="judul_proposal" value="{{ $proposal->judul_proposal }}" required>
                    </div>
                    <div class="form-group">
                        <label for="dosenPembimbing">Dosen Pembimbing</label>
                        <input type="text" class="form-control" id="dosenPembimbing" name="dosenPembimbing" value="{{ $proposal->dosenPembimbing }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nidnDosenPembimbing">NIDN Dosen Pembimbing</label>
                        <input type="text" class="form-control" id="nidnDosenPembimbing" name="nidnDosenPembimbing" value="{{ $proposal->nidnDosenPembimbing }}" required>
                    </div>
                    <div class="form-group">
                        <label for="anggota">Anggota</label>
                        <textarea class="form-control" id="anggota" name="anggota" required>{{ $proposal->anggota }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="linkproposal">Link Proposal</label>
                        <input type="url" class="form-control" id="linkproposal" name="linkproposal" value="{{ $proposal->linkproposal }}" required>
                    </div>
                    <div class="form-group">
                        <label for="reviewers">Reviewers</label>
                        <select class="form-control" id="reviewers" name="reviewers[]" multiple required>
                            @foreach($reviewers as $reviewer)
                                <option value="{{ $reviewer->id }}" {{ in_array($reviewer->id, $proposal->reviewers->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $reviewer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Proposal</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 px-4"> <!-- Membuat konten memenuhi lebar penuh -->
            <div class="content">
    <h1>Edit Item Penilaian</h1>

    {{-- <form action="{{ route('penilaian.update', $penilaian->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="kategori_id">Kategori</label>
            <select name="kategori_id" id="kategori_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" {{ $kategori->id == $penilaian->kategori_id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="kegiatan_id">Kegiatan</label>
            <select name="kegiatan_id" id="kegiatan_id" class="form-control" required>
                <option value="">Pilih Kegiatan</option>
                @foreach($kegiatans as $kegiatan)
                    <option value="{{ $kegiatan->id }}" {{ $kegiatan->id == $penilaian->kegiatan_id ? 'selected' : '' }}>{{ $kegiatan->nama_kegiatan }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="item">Item</label>
            <input type="text" name="item" id="item" class="form-control" value="{{ $penilaian->item }}" required>
        </div>
        <div class="form-group">
            <label for="item">Bobot</label>
            <input type="number" name="bobot" id="bobot" class="form-control" value="{{ $penilaian->bobot }}" required>
        </div>

        <div class="form-group">
            <label for="max_nilai">Max Nilai</label>
            <input type="number" name="max_nilai" id="max_nilai" class="form-control" value="{{ $penilaian->max_nilai }}" required min="0">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('penilaian.index') }}" class="btn btn-secondary">Kembali</a>
    </form> --}}
    <form action="{{ route('penilaian.update', [$kategoriId, $kegiatanId]) }}" method="POST">
        @csrf
        @method('PUT')
    
        <div class="form-group">
            <label for="kategori_id">Kategori</label>
            <select name="kategori_id" id="kategori_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" {{ $kategori->id == $kategoriId ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
    
        <div class="form-group">
            <label for="kegiatan_id">Kegiatan</label>
            <select name="kegiatan_id" id="kegiatan_id" class="form-control" required>
                <option value="">Pilih Kegiatan</option>
                @foreach($kegiatans as $kegiatan)
                    <option value="{{ $kegiatan->id }}" {{ $kegiatan->id == $kegiatanId ? 'selected' : '' }}>{{ $kegiatan->nama_kegiatan }}</option>
                @endforeach
            </select>
        </div>
    
        <hr>
        <div id="penilaian-wrapper">
            @foreach($penilaians as $index => $penilaian)
            <div class="penilaian-row row mb-3">
                <input type="hidden" name="penilaian[{{ $index }}][id]" value="{{ $penilaian->id }}">
                <div class="col-md-4">
                    <input type="text" name="penilaian[{{ $index }}][item]" class="form-control" placeholder="Item" value="{{ $penilaian->item }}" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="penilaian[{{ $index }}][bobot]" class="form-control" placeholder="Bobot" value="{{ $penilaian->bobot }}" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="penilaian[{{ $index }}][max_nilai]" class="form-control" placeholder="Max Nilai" value="{{ $penilaian->max_nilai }}" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-row">Hapus</button>
                </div>
            </div>
            @endforeach
        </div>
    
        {{-- <button type="button" class="btn btn-sm btn-success mb-3" id="add-penilaian">+ Tambah Penilaian</button> --}}
        <br>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('penilaian.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
    
</div>
</div>
</div>
</div>
@endsection
@push('scripts')
<script>
    let penilaianIndex = {{ count($penilaians) }};

    document.getElementById('add-penilaian').addEventListener('click', function () {
        const wrapper = document.getElementById('penilaian-wrapper');

        const newRow = document.createElement('div');
        newRow.classList.add('penilaian-row', 'row', 'mb-3');
        newRow.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="penilaian[${penilaianIndex}][item]" class="form-control" placeholder="Item" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="penilaian[${penilaianIndex}][bobot]" class="form-control" placeholder="Bobot" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="penilaian[${penilaianIndex}][max_nilai]" class="form-control" placeholder="Max Nilai" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-row">Hapus</button>
            </div>
        `;

        wrapper.appendChild(newRow);
        penilaianIndex++;
    });

    document.getElementById('penilaian-wrapper').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('.penilaian-row').remove();
        }
    });
</script>

@endpush
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 px-4"> <!-- Membuat konten memenuhi lebar penuh -->
            <div class="content">
    <h1>Tambah Item Penilaian</h1>

    {{-- <form action="{{ route('penilaian.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="kegiatan_id">Kegiatan</label>
            <select name="kegiatan_id" id="kegiatan_id" class="form-control" required>
                <option value="">Pilih Kegiatan</option>
                @foreach($kegiatan as $kegiatan)
                    <option value="{{ $kegiatan->id }}">{{ $kegiatan->nama_kegiatan }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="kategori_id">Kategori</label>
            <select name="kategori_id" id="kategori_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                <!-- akan diisi dinamis oleh JS -->
            </select>
        </div>
        <div class="form-group">
            <label for="item">Item</label>
            <input type="text" name="item" id="item" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="item">Bobot</label>
            <input type="number" name="bobot" id="bobot" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="max_nilai">Max Nilai</label>
            <input type="number" name="max_nilai" id="max_nilai" class="form-control" required min="0">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('penilaian.index') }}" class="btn btn-secondary">Kembali</a>
    </form> --}}
    <form action="{{ route('penilaian.store') }}" method="POST">
        @csrf
    
        <div class="form-group">
            <label for="kegiatan_id">Kegiatan</label>
            <select name="kegiatan_id" id="kegiatan_id" class="form-control" required>
                <option value="">Pilih Kegiatan</option>
                @foreach($kegiatan as $keg)
                    <option value="{{ $keg->id }}">{{ $keg->nama_kegiatan }}</option>
                @endforeach
            </select>
        </div>
    
        <div class="form-group">
            <label for="kategori_id">Kategori</label>
            <select name="kategori_id" id="kategori_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                <!-- kategori akan diisi dinamis via JS jika diperlukan -->
            </select>
        </div>
    
        <hr>
        <div id="penilaian-wrapper">
            <div class="penilaian-row row mb-3">
                <div class="col-md-4">
                    <input type="text" name="penilaian[0][item]" class="form-control" placeholder="Item" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="penilaian[0][bobot]" class="form-control" placeholder="Bobot" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="penilaian[0][max_nilai]" class="form-control" placeholder="Max Nilai" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-row">Hapus</button>
                </div>
            </div>
        </div>
    
        <button type="button" class="btn btn-sm btn-success mb-3" id="add-penilaian">+ Tambah Penilaian</button>
        <br>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('penilaian.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</div>
</div>
</div>
@endsection
@push('scripts')
<script>
    document.getElementById('kegiatan_id').addEventListener('change', function () {
        let kegiatanId = this.value;
        let kategoriSelect = document.getElementById('kategori_id');
        
        kategoriSelect.innerHTML = '<option value="">Memuat...</option>';
    
        if (kegiatanId) {
            fetch(`/get-kategori-by-kegiatan/${kegiatanId}`)
                .then(response => response.json())
                .then(data => {
                    kategoriSelect.innerHTML = '<option value="">Pilih Kategori</option>';
                    data.forEach(function (kategori) {
                        kategoriSelect.innerHTML += `<option value="${kategori.id}">${kategori.nama_kategori}</option>`;
                    });
                })
                .catch(error => {
                    kategoriSelect.innerHTML = '<option value="">Gagal memuat kategori</option>';
                    console.error(error);
                });
        } else {
            kategoriSelect.innerHTML = '<option value="">Pilih Kategori</option>';
        }
    });

    let penilaianIndex = 1;

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

// Event delegation untuk tombol hapus
document.getElementById('penilaian-wrapper').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('.penilaian-row').remove();
    }
});
    </script>
    
@endpush
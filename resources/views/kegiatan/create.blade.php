@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 px-4"> <!-- Membuat konten memenuhi lebar penuh -->
            <div class="content">
                <h1>Tambah Kegiatan</h1>

                <form action="{{ route('kegiatan.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama_kegiatan">Nama Kegiatan</label>
                        <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
                    </div>

                    <div id="kategori-container">
                        <h5>Kategori</h5>
                        <div class="kategori-item">
                            <div class="form-group">
                                <label for="kategori_nama_0">Nama Kategori</label>
                                <input type="text" class="form-control" id="kategori_nama_0" name="kategori[0][nama_kategori]" required>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary" id="tambah-kategori">Tambah Kategori</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let kategoriCount = 1; // Untuk menghitung jumlah kategori yang ditambahkan

    document.getElementById('tambah-kategori').addEventListener('click', function() {
        const kategoriContainer = document.getElementById('kategori-container');
        const newKategoriItem = document.createElement('div');
        newKategoriItem.classList.add('kategori-item');
        newKategoriItem.innerHTML = `
            <div class="form-group">
                <label for="kategori_nama_${kategoriCount}">Nama Kategori</label>
                <input type="text" class="form-control" id="kategori_nama_${kategoriCount}" name="kategori[${kategoriCount}][nama_kategori]" required>
            </div>
            
        `;
        kategoriContainer.appendChild(newKategoriItem);
        kategoriCount++;
    });
</script>
@endsection
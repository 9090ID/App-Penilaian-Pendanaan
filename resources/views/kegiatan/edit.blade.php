@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 px-4"> <!-- Membuat konten memenuhi lebar penuh -->
            <div class="content">
                <h1>Edit Kegiatan</h1>

                <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                
                    <div class="form-group">
                        <label for="nama_kegiatan">Nama Kegiatan</label>
                        <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" value="{{ $kegiatan->nama_kegiatan }}" required>
                    </div>
                
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" required>{{ $kegiatan->deskripsi }}</textarea>
                    </div>
                
                    <div id="kategori-container">
                        <h5>Kategori</h5>
                        @foreach($kegiatan->kategoris as $index => $kategori)
                            <div class="kategori-item mb-3 border p-2 rounded">
                                {{-- Tambahkan hidden input untuk ID --}}
                                <input type="hidden" name="kategori[{{ $index }}][id]" value="{{ $kategori->id }}">
                
                                <div class="form-group">
                                    <label for="kategori_nama_{{ $index }}">Nama Kategori</label>
                                    <input type="text" class="form-control" id="kategori_nama_{{ $index }}" name="kategori[{{ $index }}][nama_kategori]" value="{{ $kategori->nama_kategori }}" required>
                                </div>
                            </div>
                        @endforeach
                    </div>
                
                    <button type="button" class="btn btn-secondary mb-3" id="tambah-kategori">Tambah Kategori</button>
                    <br>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
                
            </div>
        </div>
    </div>
</div>

<script>
    let kategoriCount = {{ count($kegiatan->kategoris) }}; // Menghitung jumlah kategori yang ada

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
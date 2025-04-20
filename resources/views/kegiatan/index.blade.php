@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 px-4"> <!-- Membuat konten memenuhi lebar penuh -->
            <div class="content">
    <h1>Daftar Kegiatan</h1>
    <a href="{{ route('kegiatan.create') }}" class="btn btn-primary mb-3">Tambah Kegiatan</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kegiatan</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kegiatan as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->nama_kegiatan }}</td>
                    <td>{{ $item->deskripsi }}</td>
                    <td>
                        <a href="{{ route('kegiatan.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('kegiatan.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tautan Pagination -->
    {{-- <div class="d-flex justify-content-center">
        {{ $kegiatan->links() }} <!-- Menampilkan tautan pagination -->
    </div> --}}
</div>
</div>
</div>
</div>
@endsection
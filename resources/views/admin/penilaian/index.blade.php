@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 px-4">
            <div class="content">
                <h1>Daftar Item Penilaian</h1>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('penilaian.tambahNilai') }}" class="btn btn-danger mb-4">Tambah Item Penilaian</a>
                    
                <table id="penilaianTable" class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kategori</th>
                            <th>Kegiatan</th>
                            <th>Item</th>
                            <th>Bobot</th>
                            <th>Max Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penilaians as $penilaian)
                            <tr>
                                <td>{{ $penilaian->id }}</td>
                                <td>{{ $penilaian->kategori->nama_kategori ?? 'Tidak Ada'  }}</td>
                                <td>{{ $penilaian->kegiatan->nama_kegiatan }}</td>
                                <td>{{ $penilaian->item }}</td>
                                <td>{{ $penilaian->bobot }}</td>
                                <td>{{ $penilaian->max_nilai }}</td>
                                <td>
                                    <a href="{{ route('penilaian.edit', [$penilaian->kategori_id, $penilaian->kegiatan_id]) }}" class="btn btn-sm btn-warning">Edit</a>

                                    <form action="{{ route('penilaian.destroy', $penilaian->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')"><i class="fas fa-trash"></i> Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    // Inisialisasi DataTables untuk tabel dengan id 'rekapTable'
    $(document).ready(function() {
        $('#penilaianTable').DataTable();
    });

</script>
@endpush
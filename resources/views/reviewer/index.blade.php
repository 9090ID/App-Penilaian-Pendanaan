@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 px-4">
            <div class="content">
                <h1>Daftar Proposal yang Dinilai</h1>
                <hr>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <table id="proposalTable" class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Ketua</th>
                            <th>NIM Ketua</th>
                            <th>Dosen Pembimbing</th>
                            <th>Judul Proposal</th>
                            <th>Kategori Kegiatan</th>
                            <th>Penilaian Anda</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proposals as $proposal)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $proposal->namaKetua ?? 'N/A' }}</td>
                                <td>{{ $proposal->nimKetua ?? 'N/A' }}</td>
                                <td>{{ $proposal->dosenPembimbing ?? 'N/A' }}</td>
                                <td>{{ $proposal->judul_proposal }}</td>
                                <td>{{ $proposal->kegiatan->nama_kegiatan ?? 'N/A' }} <span class="badge badge-danger">({{ $proposal->kategori->nama_kategori ?? 'N/A' }})</span> </td>
                                <td>
                                    {{ $proposal->aggregated_score > 0 ? number_format($proposal->aggregated_score, 2) : 'Belum Dinilai' }}

                                </td>
                                <td>
                                    @if($proposal->evaluation_scores->where('reviewer_id', $userId)->isNotEmpty())
                                        <button class="btn btn-secondary" disabled>Sudah Dinilai</button>
                                    @else
                                        <a href="{{ route('evaluation.create', ['proposal_id' => $proposal->id]) }}" class="btn btn-primary">Nilai Proposal</a>
                                    @endif
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
    $(document).ready(function() {
        $('#proposalTable').DataTable(); // Inisialisasi DataTable
    });
</script>
@endpush
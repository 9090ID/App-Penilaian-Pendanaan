@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 px-4">
                <div class="content">
                    <h1>Daftar Proposal</h1>
                    <form action="{{ route('proposal.import') }}" method="POST" enctype="multipart/form-data" class="md-4">
                        @csrf
                        <div class="form-group">
                            <label for="file">Upload File Excel</label>
                            <input type="file" name="file" id="file" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Import Excel</button>
                    </form>
                    <br>
                    {{-- <a href="{{ route('proposal.create') }}" class="btn btn-primary">Tambah Proposal</a> --}}
                    <hr>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table id="proposalTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kegiatan</th>
                                <th>Kategori</th>
                                <th>Nama Ketua</th>
                                <th>Judul Proposal</th>
                                <th>Dosen Pembimbing</th>
                                <th>Link Proposal</th>
                                <th>Reviewers</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>


                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Editing Proposal -->
    @foreach ($proposals as $proposal)
        <!-- Modal for Editing Proposal -->
        <div class="modal fade" id="editProposalModal{{ $proposal->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editProposalModalLabel" aria-hidden="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProposalModalLabel">Edit Proposal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('proposal.update', $proposal->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="kegiatan_id">Kegiatan</label>
                                <input type="text" class="form-control" id="kegiatan_id"
                                    value="{{ $proposal->kegiatan->nama_kegiatan }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="kategori_id">Kategori</label>
                                <input type="text" class="form-control" id="kategori_id"
                                    value="{{ $proposal->kategori->nama_kategori ?? 'Tidak Ada' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nimKetua">NIM Ketua</label>
                                <input type="text" class="form-control" id="nimKetua" name="nimKetua"
                                    value="{{ $proposal->nimKetua }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="judul_proposal">Judul Proposal</label>
                                <input type="text" class="form-control" id="judul_proposal" name="judul_proposal"
                                    value="{{ $proposal->judul_proposal }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="reviewers">Reviewers</label>
                                <select class="form-control" id="reviewers" name="reviewers[]" multiple required>
                                    @foreach ($reviewers as $reviewer)
                                        <option value="{{ $reviewer->id }}"
                                            {{ in_array($reviewer->id, $proposal->reviewers->pluck('id')->toArray()) ? 'selected' : '' }}>
                                            {{ $reviewer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Set Reviewer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@push('scripts')
    <script>
       $(document).ready(function () {
        $('#proposalTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('get.datatables') }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'kegiatan', name: 'kegiatan.nama_kegiatan' },
                { data: 'kategori', name: 'kategori.nama_kategori' },
                { data: 'namaKetua', name: 'namaKetua' },
                { data: 'judul_proposal', name: 'judul_proposal' },
                { data: 'dosenPembimbing', name: 'dosenPembimbing' },
                { data: 'linkproposal', name: 'linkproposal', orderable: false, searchable: false },
                { data: 'reviewers', name: 'reviewers', orderable: false, searchable: false },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
            ]
        });
    });
    </script>
@endpush

@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 ">
                <div class="content">
                    <h3 class="mb-4 text-danger">Penilaian Proposal: "{{ $proposal->judul_proposal }}"</h3>
                    <hr>

                    <div class="col-md-6 ">

                        <tr>
                            <td><strong>Nama Reviewer</strong></td>
                            <td>:</td>
                            <td>{{ Auth::user()->name }}</td>
                        </tr>
                        <table class="table table-bordered">
                            <h4 class="py-3 text-danger">Detail Pendaftar</h4>
                            <tr>
                                <td><strong>Nama Ketua</strong></td>
                                <td>:</td>
                                <td>{{ $proposal->namaKetua }}</td>
                            </tr>
                            <tr>
                                <td><strong>NIM Ketua</strong></td>
                                <td>:</td>
                                <td>{{ $proposal->nimKetua }}</td>
                            </tr>
                            <tr>
                                <td><strong>Dosen Pembimbing</strong></td>
                                <td>:</td>
                                <td>{{ $proposal->dosenPembimbing }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Kegiatan</strong></td>
                                <td>:</td>
                                <td>{{ $proposal->kegiatan->nama_kegiatan ?? 'Tidak Ada' }} <span class="badge badge-danger">({{ $proposal->kategori->nama_kategori ?? 'Tidak Ada' }})</span> </td>
                            </tr>
                            <tr>
                                <td><strong>Link Proposal</strong></td>
                                <td>:</td>
                                <td><a href="{{ asset($proposal->linkproposal) }}" target="_blank">Lihat Proposal PDF</a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Anggota</strong></td>
                                <td>:</td>
                                <td>
                                    @if (!empty($proposal->anggota) && is_array($proposal->anggota))
                                        @foreach ($proposal->anggota as $anggota)
                                            {{ $anggota['Nama'] ?? 'Nama tidak tersedia' }}
                                            ({{ $anggota['NIM'] ?? 'NIM tidak tersedia' }})
                                            -
                                            {{ $anggota['Prodi'] ?? 'Prodi tidak tersedia' }}
                                            <br>
                                        @endforeach
                                    @else
                                        <p>Tidak ada anggota yang terdaftar.</p>
                                    @endif
                                </td>
                            </tr>
                        </table>

                    </div>

                    <!-- Menampilkan Anggota -->


                    <div class="container rounded">
                        <h3 class="py-3 mb-3 mx-3 text-center">Form Penilaian Proposal</h3>
                        <form action="{{ route('evaluation.store') }}" method="POST" class="border p-4 rounded shadow">
                            @csrf
                            <input type="hidden" name="proposal_id" value="{{ $proposal->id }}">

                            <table class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kriteria Penilaian</th>
                                        <th>Bobot</th>
                                        <th>Skor</th>
                                        <th>Total</th> <!-- Kolom Total -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td> <!-- Menampilkan nomor urut -->
                                            <td>{{ $item->item }}</td>
                                            <td>{{ $item->bobot }}</td> <!-- Menampilkan bobot -->
                                            <td>
                                                <input type="number" name="scores[{{ $item->id }}]"
                                                    class="form-control score-input" data-bobot="{{ $item->bobot }}"
                                                    data-max="{{ $item->max_nilai }}" min="0"
                                                    placeholder="0 - {{ $item->max_nilai }}" required>

                                                <small class="text-danger error-message d-none">Nilai tidak boleh lebih dari
                                                    {{ $item->max_nilai }}</small>
                                            </td>
                                            <td class="total-cell">0</td> <!-- Menampilkan total -->
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-right"><strong>Total Keseluruhan:</strong></td>
                                        <td id="overall-total">0</td> <!-- Menampilkan total keseluruhan -->
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><strong>Total Akhir: <span
                                                    class="badge badge-danger">(Total Keseluruhan/Total Bobot
                                                    Maksimum*100)</span></strong></td>
                                        <td id="totalAkhir">0</td> <!-- Menampilkan total akhir -->
                                    </tr>
                                </tfoot>

                            </table>

                            <div class="mt-4">
                                <h5>Rekomendasi Akhir</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="recommendation"
                                                value="Layak Diloloskan" id="recommendation1" required>
                                            <label class="form-check-label" for="recommendation1">
                                                Layak Diloloskan
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="recommendation"
                                                value="Lolos dengan Perbaikan diCatatan" id="recommendation2" required>
                                            <label class="form-check-label" for="recommendation2">
                                                Lolos dengan Perbaikan di Catatan
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="recommendation"
                                                value="Tidak Layak diLoloskan" id="recommendation3" required>
                                            <label class="form-check-label" for="recommendation3">
                                                Tidak Layak di Loloskan
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label for="final_notes">Catatan Akhir:</label>
                                <textarea class="form-control" id="final_notes" name="comments" rows="3"
                                    placeholder="Masukkan catatan akhir di sini..."></textarea>
                            </div>
                            <div class="alert alert-danger mt-3">
                                <strong>Perhatian:</strong> Pastikan semua penilaian sudah benar sebelum mengirimkan.
                                Penilaian yang sudah dikirim tidak dapat diubah.
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Penilaian</button>

                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <script>
       document.querySelectorAll('.score-input').forEach(input => {
    input.addEventListener('input', function() {
        const bobot = parseFloat(this.getAttribute('data-bobot'));
        const maxNilai = parseFloat(this.getAttribute('data-max'));
        const skor = parseFloat(this.value) || 0;
        const totalCell = this.closest('tr').querySelector('.total-cell');

        // Validasi skor maksimal
        if (skor > maxNilai) {
            this.classList.add('is-invalid');

            // Tambahkan pesan error kalau belum ada
            if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('error-message')) {
                const error = document.createElement('small');
                error.classList.add('text-danger', 'error-message');
                error.textContent = `Nilai tidak boleh lebih dari ${maxNilai}`;
                this.parentNode.appendChild(error);
            }
        } else {
            this.classList.remove('is-invalid');
            const errorMsg = this.parentNode.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.remove();
            }
        }

        // Hitung total baris
        const totalBaris = (bobot * skor).toFixed(0);
        totalCell.textContent = totalBaris;

        // Hitung total keseluruhan
        let overallTotal = 0;
        let totalBobot = 0;
        let totalBobotMaksimum = 0; // Inisialisasi total bobot maksimum

        document.querySelectorAll('.total-cell').forEach(cell => {
            const totalValue = parseFloat(cell.textContent) || 0;
            overallTotal += totalValue;

            // Menjumlahkan bobot untuk normalisasi total
            const row = cell.closest('tr');
            const bobotInRow = parseFloat(row.querySelector('.score-input').getAttribute('data-bobot'));
            totalBobot += bobotInRow;

            // Menghitung total bobot maksimum (max_nilai * bobot)
            const maxNilaiInRow = parseFloat(row.querySelector('.score-input').getAttribute('data-max'));
            totalBobotMaksimum += (maxNilaiInRow * bobotInRow);
        });

        // Hitung total akhir dengan rumus (overallTotal / totalBobotMaksimum) * 100
        const totalAkhir = totalBobotMaksimum > 0 ? ((overallTotal / totalBobotMaksimum) * 100).toFixed(0) : 0;

        // Update total akhir dan total keseluruhan
        document.getElementById('overall-total').textContent = overallTotal.toFixed(0);
        document.getElementById('totalAkhir').textContent = totalAkhir;
        
    });
});

document.getElementById('evaluationForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let hasError = false;

    // Cek apakah ada input yang tidak valid
    document.querySelectorAll('.score-input').forEach(input => {
        const max = parseFloat(input.getAttribute('data-max'));
        const val = parseFloat(input.value) || 0;

        if (val > max) {
            hasError = true;
            input.classList.add('is-invalid');

            if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('error-message')) {
                const error = document.createElement('small');
                error.classList.add('text-danger', 'error-message');
                error.textContent = `Nilai tidak boleh lebih dari ${max}`;
                input.parentNode.appendChild(error);
            }
        }
    });

    if (hasError) {
        swal("Oops!", "Ada nilai yang melebihi batas maksimum. Mohon periksa kembali.", "error");
        return;
    }

    // Jika tidak ada error, konfirmasi simpan
    swal({
        title: "Konfirmasi",
        text: "Apakah Anda yakin ingin menyimpan penilaian ini? Setelah disimpan, tidak dapat diubah.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willSave) => {
        if (willSave) {
            this.submit(); // Submit jika disetujui
        }
    });
});

    </script>
@endsection

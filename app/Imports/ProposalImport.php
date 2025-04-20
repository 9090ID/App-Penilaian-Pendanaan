<?php

namespace App\Imports;

use App\Models\Proposal;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class ProposalImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Validasi data pada setiap baris
            $validator = Validator::make($row->toArray(), [
                'kegiatan_id' => 'required|exists:kegiatan,id',
                'kategori_id' => 'required|exists:kategori,id',
                'nama_ketua' => 'required|string|max:255',
                'nim_ketua' => 'required|string|max:20',
                'nohp_ketua' => 'max:15',
                'prodi_ketua' => 'required|string|max:255',
                'fakultas_ketua' => 'required|string|max:255',
                'judul_proposal' => 'required|string|max:255',
                'dosen_pembimbing' => 'required|string|max:255',
                'nidn_dosen_pembimbing' => 'required|string|max:20',
                'anggota' => 'required|json',
                'linkproposal' => 'required|url',
            ]);

            if ($validator->fails()) {
                // Menangkap error validasi dan menampilkan pesan error
                $errors = $validator->errors();
                // Log atau proses error
                Log::error('Validation errors: ', $errors->toArray());

                // Anda bisa memutuskan apakah ingin melanjutkan proses atau tidak
                continue; // Lanjutkan ke baris berikutnya atau beri feedback ke pengguna
            }

            // Simpan data proposal
            $proposal = Proposal::create([
                'kegiatan_id' => $row['kegiatan_id'],
                'kategori_id' => $row['kategori_id'],
                'namaKetua' => $row['nama_ketua'],
                'nimKetua' => $row['nim_ketua'],
                'nohpKetua' => $row['nohp_ketua'],
                'prodiKetua' => $row['prodi_ketua'],
                'fakultasKetua' => $row['fakultas_ketua'],
                'judul_proposal' => $row['judul_proposal'],
                'dosenPembimbing' => $row['dosen_pembimbing'],
                'nidnDosenPembimbing' => $row['nidn_dosen_pembimbing'],
                'anggota' => $row['anggota'],
                'linkproposal' => $row['linkproposal'],
            ]);

            // Menambahkan reviewer
            // Misalkan $row['reviewers'] berisi string reviewer yang dipisahkan dengan koma
            $reviewerIds = explode(',', $row['reviewers']); // Memisahkan string menjadi array

            // Menghapus spasi dan memastikan semua ID adalah integer
            $reviewerIds = array_map('trim', $reviewerIds); // Menghapus spasi di sekitar ID
            $reviewerIds = array_map('intval', $reviewerIds); // Mengonversi semua ID menjadi integer

            // Mengaitkan reviewer ke proposal
            $proposal->reviewers()->attach($reviewerIds);
        }
    }
}

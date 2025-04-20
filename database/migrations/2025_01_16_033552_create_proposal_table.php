<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proposal', function (Blueprint $table) {
            $table->id(); // ID proposal
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->onDelete('cascade'); // Relasi ke tabel kegiatan
            $table->string('kategori'); // Kategori proposal
            $table->string('namaKetua'); // Nama ketua proposal
            $table->string('nimKetua'); // NIM ketua proposal
            $table->string('nohpKetua'); // Nomor HP ketua proposal
            $table->string('prodiKetua'); // Program studi ketua proposal
            $table->string('fakultasKetua'); // Fakultas ketua proposal
            $table->string('judul_proposal'); // Judul proposal
            $table->string('dosenPembimbing'); // Nama dosen pembimbing
            $table->string('nidnDosenPembimbing'); // NIDN dosen pembimbing
            $table->json('anggota'); // Menyimpan anggota dalam format JSON
            $table->string('linkproposal'); // Link untuk proposal
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal');
    }
};

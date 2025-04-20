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
        Schema::create('kategori', function (Blueprint $table) {
            $table->id(); // ID kategori
            $table->string('nama_kategori'); // Nama kategori
            $table->json('rubrik_penilaian'); // Rubrik penilaian dalam format JSON
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->onDelete('cascade'); // Relasi ke tabel kegiatan
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};

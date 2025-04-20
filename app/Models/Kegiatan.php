<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;
    // Tentukan nama tabel jika tidak sesuai dengan konvensi
    protected $table = 'kegiatan';

    // Tentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'nama_kegiatan',
        'deskripsi',
    ];

    // Jika Anda ingin mengonversi rubrik_penilaian ke array saat diambil
    protected $casts = [
        'rubrik_penilaian' => 'array', // Mengonversi JSON ke array
    ];
    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'kegiatan_id');
    }
    public function kategoris()
    {
        return $this->hasMany(Kategori::class, 'kegiatan_id');
    }
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}

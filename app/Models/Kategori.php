<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'kategori';
    protected $fillable = [
        'nama_kategori',
        'kegiatan_id',
    ];
     
    // Relasi banyak ke satu dengan Kegiatan
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'kategori_id');
    }
    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }
    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class, 'kategori_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;
    protected $table = 'penilaian';
    protected $fillable = [
        'kategori_id',
        'kegiatan_id',
        'item',
        'max_nilai',
        'bobot',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
}
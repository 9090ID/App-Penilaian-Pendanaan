<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;
    protected $fillable = ['kegiatan_id', 'namaKetua', 'nimKetua', 'dynamic_fields'];

    protected $casts = [
        'dynamic_fields' => 'array',  // Mengkonversi data dinamis menjadi array
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;
    protected $table = 'proposal';
    protected $fillable = [
        'kegiatan_id',
        'kategori_id',
        'namaKetua',
        'nimKetua',
        'nohpKetua',
        'prodiKetua',
        'fakultasKetua',
        'judul_proposal',
        'dosenPembimbing',
        'nidnDosenPembimbing',
        'anggota',
        'linkproposal',
    ];

    // Jika Anda ingin mengonversi rubrik_penilaian ke array saat diambil
    protected $casts = [
        'anggota' => 'array', // Mengonversi JSON ke array
    ];

    // Relasi ke model Kegiatan
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
    public function evaluation_scores()
    {
        return $this->hasMany(EvaluationScore::class);
    }

    public function reviewers()
    {
        return $this->belongsToMany(User::class, 'proposal_reviewer', 'proposal_id', 'user_id');
    }
    // Model Proposal


    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }
    

    
   
}

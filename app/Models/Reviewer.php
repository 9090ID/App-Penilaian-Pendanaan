<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviewer extends Model
{
    use HasFactory;
    protected $table = 'proposal_reviewer';
    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'proposal_id',
        'user_id',
       
        // Tambahkan kolom lain yang relevan
    ];



    // Relasi dengan Proposal
    public function proposals()
    {
        return $this->belongsToMany(Proposal::class, 'proposal_reviewer', 'reviewer_id', 'proposal_id');
    }

    // Relasi dengan EvaluationScore
    public function evaluationScores()
    {
        return $this->hasMany(EvaluationScore::class);
    }

    // Relasi dengan Penilaian
    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }
}
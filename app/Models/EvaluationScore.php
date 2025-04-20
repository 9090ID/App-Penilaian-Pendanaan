<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reviewer;

class EvaluationScore extends Model
{
    use HasFactory;
    protected $table = 'evaluation_scores';
    protected $fillable = [
        'proposal_id',
        'reviewer_id',
        'penilaian_id',
        'score',
        'comments',
        'recommendation',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id'); // Adjust 'reviewer_id' as necessary
    }

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
    
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Proposal;
use App\Models\Kategori;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Log;

class ReviewerController extends Controller
{
    public function dash()
    {
        return view('reviewer.dashboard');
    }
    // public function index()
    // {
    //     $userId = auth()->id();

    //     // Ambil proposal yang terkait dengan reviewer yang sedang login
    //     $proposals = Proposal::whereHas('reviewers', function ($query) use ($userId) {
    //         $query->where('user_id', $userId);
    //     })
    //         ->with(['kategori', 'evaluation_scores.penilaian' => function ($query) {
    //             // Eager load penilaian to get the bobot
    //         }])
    //         ->get();

    //     // Agregasi skor untuk setiap proposal
    //     foreach ($proposals as $proposal) {
    //         $proposal->aggregated_score = 0;

    //         // Loop through each evaluation score
    //         foreach ($proposal->evaluation_scores as $score) {
    //             // Check if the score belongs to the logged-in reviewer
    //             if ($score->reviewer_id == $userId) {
    //                 // Check if the penilaian exists and has a bobot
    //                 if ($score->penilaian && $score->penilaian->bobot !== null) {
    //                     $calculatedScore = $score->score * $score->penilaian->bobot; // Mengalikan skor dengan bobot
    //                     $proposal->aggregated_score += $calculatedScore; // Tambahkan ke aggregated score

    //                     // Log the individual score and weight for debugging
    //                     Log::info("Proposal ID: {$proposal->id}, Score: {$score->score}, Bobot: {$score->penilaian->bobot}, Calculated: {$calculatedScore}");
    //                 } else {
    //                     // Log if bobot is missing
    //                     Log::info("Missing bobot for proposal ID: {$proposal->id}, score: {$score->score}");
    //                 }
    //             }
    //         }

    //         // Log the final aggregated score for each proposal
    //         Log::info("Final Aggregated Score for Proposal ID: {$proposal->id} is: {$proposal->aggregated_score}");
    //     }

    //     return view('reviewer.index', compact('proposals', 'userId'));
    // }
    // public function index()
    // {
    //     $userId = auth()->id();

    //     // Ambil proposal yang terkait dengan reviewer yang sedang login
    //     $proposals = Proposal::whereHas('reviewers', function ($query) use ($userId) {
    //         $query->where('user_id', $userId);
    //     })
    //         ->with(['kategori', 'evaluation_scores.penilaian' => function ($query) {
    //             // Eager load penilaian to get the bobot
    //         }])
    //         ->get();
    //     foreach ($proposals as $proposal) {
    //         $proposal->aggregated_score = 0; // Initialize aggregated score

    //         // Get the kegiatan_id from the proposal
    //         $kegiatan_id = $proposal->kegiatan_id;
    //         // Get the kategori_id from the proposal
    //         $kategori_id = $proposal->kategori_id;

    //         // Calculate the total possible score (1350) for the specific kegiatan_id
    //         $totalPossibleScore = Penilaian::where('kegiatan_id', $kegiatan_id)
    //             ->where('kategori_id', $kategori_id)
    //             ->get()
    //             ->sum(function ($penilaian) {
    //                 Log::info("max_nilai: {$penilaian->max_nilai}, bobot: {$penilaian->bobot}"); // Log max_nilai and bobot
    //                 return $penilaian->max_nilai * $penilaian->bobot;
    //             });

    //         // dd($totalPossibleScore);
    //         // Loop through each evaluation score to calculate total score
    //         foreach ($proposal->evaluation_scores as $score) {
    //             // Check if the score belongs to the logged-in reviewer
    //             if ($score->reviewer_id == $userId) {
    //                 // Get the penilaian (evaluation criteria)
    //                 $penilaian = $score->penilaian;

    //                 // Check if penilaian exists and has a bobot (weight)
    //                 if ($penilaian && $penilaian->bobot !== null && $score->score !== 0) {
    //                     // Calculate the score using the new formula
    //                     $calculatedScore = (($penilaian->max_nilai * $penilaian->bobot) / $totalPossibleScore) * 100;

    //                     // Add the calculated score to the aggregated score
    //                     $proposal->aggregated_score += $calculatedScore;

    //                     // Log the score, max_nilai, bobot, and calculated result for debugging
    //                     Log::info("Proposal ID: {$proposal->id}, Score: {$score->score}, Max Nilai: {$penilaian->max_nilai}, Bobot: {$penilaian->bobot}, Calculated: {$calculatedScore}");
    //                 } else {
    //                     // Log if the bobot or score is missing
    //                     Log::info("Missing bobot or score for proposal ID: {$proposal->id}, Penilaian ID: {$penilaian->id}");
    //                 }
    //             }
    //         }

    //         // Log the final aggregated score for each proposal
    //         Log::info("Final Aggregated Score for Proposal ID: {$proposal->id} is: {$proposal->aggregated_score}");
    //     }

    //     // Return the proposals and userId to the view
    //     return view('reviewer.index', compact('proposals', 'userId'));
    // }
    
    // public function index()
    // {
    //     $userId = auth()->id();
    
    //     $proposals = Proposal::whereHas('reviewers', function ($query) use ($userId) {
    //         $query->where('user_id', $userId);
    //     })
    //     ->with(['kategori', 'evaluation_scores.penilaian'])
    //     ->get();
    
    //     $totalFinalScore = 0;
    
    //     foreach ($proposals as $proposal) {
    //         $proposal->aggregated_score = 0;
    
    //         $kegiatan_id = $proposal->kegiatan_id;
    //         $kategori_id = $proposal->kategori_id;
    //         dd($kegiatan_id, $kategori_id);
    //         $penilaians = Penilaian::where('kegiatan_id', $kegiatan_id)
    //             ->where('kategori_id', $kategori_id)
    //             ->get();
    
    //         $totalPossibleScore = $penilaians->sum(function ($penilaian) {
    //             return $penilaian->max_nilai * $penilaian->bobot;
    //         });
    
    //         $validPenilaianIds = $penilaians->pluck('id')->toArray();
            
    //         foreach ($proposal->evaluation_scores as $score) {
    //             if ($score->reviewer_id == $userId) {
    //                 $penilaian = $score->penilaian;
            
    //                 if ($penilaian && $penilaian->bobot !== null && $score->score !== null) {
    //                     // Rumus yang diminta: (score * bobot) / totalPossibleScore * 100
    //                     $calculatedScore = (($score->score * $penilaian->bobot) / $totalPossibleScore) * 100;
            
    //                     $proposal->aggregated_score += $calculatedScore;
            
    //                     Log::info("Proposal ID: {$proposal->id}, Score: {$score->score}, Bobot: {$penilaian->bobot}, Calculated: {$calculatedScore}");
    //                 } else {
    //                     $penilaianId = $penilaian ? $penilaian->id : 'null';
    //                     Log::info("Missing bobot or score for Proposal ID: {$proposal->id}, Penilaian ID: {$penilaianId}");
    //                 }
    //             }
    //         }
            
    
    //         // Tambahkan nilai proposal ini ke total seluruh nilai reviewer
    //         $totalFinalScore += $proposal->aggregated_score;
    
    //         Log::info("Final Aggregated Score for Proposal ID: {$proposal->id} = {$proposal->aggregated_score}");
    //     }
    
    //     // Kirim juga totalFinalScore ke view
    //     return view('reviewer.index', compact('proposals', 'userId', 'totalFinalScore'));
    // }
    
    // public function index()
    // {
    //     $userId = auth()->id();
    
    //     $proposals = Proposal::whereHas('reviewers', function ($query) use ($userId) {
    //         $query->where('user_id', $userId);
    //     })
    //     ->with(['kategori', 'evaluation_scores.penilaian'])
    //     ->get();
    
    //     $totalFinalScore = 0;
    
    //     foreach ($proposals as $proposal) {
    //         $proposal->aggregated_score = 0;
    
    //         $kegiatan_id = $proposal->kegiatan_id;
            
    //         // Ambil seluruh penilaian dari kegiatan tersebut (semua kategori termasuk)
    //         $penilaiansDalamKegiatan = Penilaian::where('kegiatan_id', $kegiatan_id)->get();
                
    //         // Hitung total skor maksimal dari semua bobot x max_nilai
    //         $totalPossibleScore = $penilaiansDalamKegiatan->sum(function ($penilaian) {
    //             return $penilaian->max_nilai * $penilaian->bobot;
    //         });
            
    //         Log::info("Total Possible Score for kegiatan_id {$kegiatan_id} = {$totalPossibleScore}");
    
    //         foreach ($proposal->evaluation_scores as $score) {
    //             if ($score->reviewer_id == $userId) {
    //                 $penilaian = $score->penilaian;
    
    //                 if ($penilaian && $penilaian->bobot !== null && $score->score !== null) {
    //                     $calculatedScore = (($score->score * $penilaian->bobot) / $totalPossibleScore) * 100;
    
    //                     $proposal->aggregated_score += $calculatedScore;
    
    //                     Log::info("Proposal ID: {$proposal->id}, Score: {$score->score}, Bobot: {$penilaian->bobot}, Calculated: {$calculatedScore}");
    //                 } else {
    //                     $penilaianId = $penilaian ? $penilaian->id : 'null';
    //                     Log::info("Missing bobot or score for Proposal ID: {$proposal->id}, Penilaian ID: {$penilaianId}");
    //                 }
    //             }
    //         }
    
    //         $totalFinalScore += $proposal->aggregated_score;
    
    //         Log::info("Final Aggregated Score for Proposal ID: {$proposal->id} = {$proposal->aggregated_score}");
    //     }
    
    //     return view('reviewer.index', compact('proposals', 'userId', 'totalFinalScore'));
    // }
    
    public function index()
    {
        $userId = auth()->id();
    
        $proposals = Proposal::whereHas('reviewers', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with(['kategori', 'evaluation_scores.penilaian'])
        ->get();
    
        $totalFinalScore = 0;
    
        foreach ($proposals as $proposal) {
            $proposal->aggregated_score = 0;
    
            $kegiatan_id = $proposal->kegiatan_id;
            $kategori_id = $proposal->kategori_id;
    
            // Ambil semua penilaian terkait kategori & kegiatan ini
            $penilaians = Penilaian::where('kegiatan_id', $kegiatan_id)
                ->where('kategori_id', $kategori_id)
                ->get();
    
            // Hitung pembagi: total bobot * max_nilai
            $totalMaxBobot = $penilaians->sum(function ($penilaian) {
                return $penilaian->max_nilai * $penilaian->bobot;
            });
    
            Log::info("Total Max Bobot untuk kegiatan_id {$kegiatan_id} dan kategori_id {$kategori_id}: {$totalMaxBobot}");
    
            $nilaiReviewerIni = 0;
    
            foreach ($proposal->evaluation_scores as $score) {
                if ($score->reviewer_id == $userId) {
                    $penilaian = $score->penilaian;
    
                    if ($penilaian && $penilaian->bobot !== null && $score->score !== null) {
                        $subtotal = $score->score * $penilaian->bobot;
                        $nilaiReviewerIni += $subtotal;
    
                        Log::info("Proposal ID: {$proposal->id}, Reviewer: {$userId}, Score: {$score->score}, Bobot: {$penilaian->bobot}, Subtotal: {$subtotal}");
                    }
                }
            }
    
            if ($totalMaxBobot > 0) {
                $proposal->aggregated_score = ($nilaiReviewerIni / $totalMaxBobot) * 100;
            } else {
                $proposal->aggregated_score = 0;
            }
    
            $totalFinalScore += $proposal->aggregated_score;
    
            Log::info("Final Aggregated Score for Proposal ID: {$proposal->id} = {$proposal->aggregated_score}");
        }
    
        return view('reviewer.index', compact('proposals', 'userId', 'totalFinalScore'));
    }
    
    public function edit($id)
    {
        $proposal = Proposal::findOrFail($id);
        $kategoris = Kategori::all(); // Ambil semua kategori untuk dropdown
        return view('reviewer.edit', compact('proposal', 'kategoris'));
    }
}

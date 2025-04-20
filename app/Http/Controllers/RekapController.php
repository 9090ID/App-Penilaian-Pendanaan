<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\Kegiatan;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Kategori;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapProposalExport;
use Illuminate\Support\Facades\Log;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil semua kegiatan dan kategori untuk dropdown
        $kegiatanList = Kegiatan::all();
        $kategoriList = Kategori::all();

        // Mengambil tahun yang unik berdasarkan created_at di Proposal
        $years = Proposal::selectRaw('YEAR(created_at) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('rekap.index', compact('kegiatanList', 'kategoriList', 'years')); // Pastikan variabel dikirim
    }


    public function getFilteredData(Request $request)
    {
        // Start the query with the necessary relationships
        $query = Proposal::with(['kegiatan', 'kategori', 'evaluation_scores.penilaian', 'evaluation_scores.reviewer']);

        // Apply filters based on request parameters
        if ($request->kegiatan) {
            $query->where('kegiatan_id', $request->kegiatan);
        }

        if ($request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->tahun) {
            $query->whereYear('created_at', $request->tahun);
        }

        // Get proposal data
        $proposals = $query->get();

        // Process to calculate combined score
        $result = $proposals->map(function ($proposal) {
            $totalNilai = 0;
            $totalBobot = 0;
            $reviewerIds = []; // Array to store unique reviewer IDs

            // Log the evaluation scores for debugging
            foreach ($proposal->evaluation_scores as $score) {
                Log::info("Proposal ID: {$proposal->id}, Score ID: {$score->id}, Score: {$score->score}, Penilaian ID: {$score->penilaian_id}");
            }

            foreach ($proposal->evaluation_scores as $score) {
                // Ensure the penilaian relationship is loaded
                if ($score->penilaian) {
                    $bobot = $score->penilaian->bobot; // Get weight from penilaian
                    $skor = $score->score; // Get score from evaluation_scores

                    // Log the values being processed
                    Log::info("Proposal ID: {$proposal->id}, Score: {$skor}, Bobot: {$bobot}");

                    // Calculate total score and total weight
                    if ($skor !== null) {
                        $totalNilai += $skor * $bobot; // Multiply score by weight
                        $totalBobot += $bobot; // Sum the weights

                        // Store unique reviewer IDs
                        if (!in_array($score->reviewer_id, $reviewerIds)) {
                            $reviewerIds[] = $score->reviewer_id; // Add unique reviewer ID
                        }
                    }
                } else {
                    Log::info("Proposal ID: {$proposal->id} has no penilaian for Score ID: {$score->id}");
                }
            }

            // Calculate the combined score based on the number of unique reviewers
            $reviewerCount = count($reviewerIds); // Count unique reviewers
            $nilaiGabungan = $reviewerCount > 0 ? $totalNilai / $reviewerCount : 0;

            // Log the total values
            Log::info("Proposal ID: {$proposal->id}, Total Nilai: {$totalNilai}, Total Bobot: {$totalBobot}, Nilai Gabungan: {$nilaiGabungan}");

            // Combine all reviewers' comments and recommendations
            $dataReviewer = $proposal->evaluation_scores->map(function ($score) {
                $reviewerName = $score->reviewer ? $score->reviewer->name : 'Unknown Reviewer';

                // Only take the first comment and recommendation for each reviewer
                $comment = $score->comments ? '<b>Komentar: </b>' . $score->comments : 'Komentar: No Komentar ';
                $recommendation = $score->recommendation ? '<b>Rekomendasi: </b>' . $score->recommendation : 'Rekomendasi: No recommendation';

                return '<b class="text-danger">' . $reviewerName . ':</b> <br/> ' . $comment . '<br/>' . $recommendation . '<br/>';
            });

            // Get unique reviewers and their first comment and recommendation
            $uniqueReviewers = $dataReviewer->unique()->implode(' <br/> '); // Join all unique reviewer data with a separator

            return [
                'judul_proposal' => $proposal->judul_proposal,
                'namaKetua' => $proposal->namaKetua,
                'nimKetua' => $proposal->nimKetua,
                'nama_kegiatan' => $proposal->kegiatan->nama_kegiatan,
                'kategori_kegiatan' => $proposal->kategori->nama_kategori,
                'skor' => $nilaiGabungan,
                'komentar_dan_rekomendasi' => $uniqueReviewers,
            ];
        });

        return response()->json([
            'data' => $result
        ]);
    }

    public function getKegiatan()
    {
        $kegiatan = Kegiatan::all(); // Ambil semua data kegiatan
        return response()->json($kegiatan); // Kembalikan sebagai JSON
    }
    public function getCategoriesByKegiatan(Request $request)
    {
        $kegiatanId = $request->input('kegiatan_id');
        $categories = Kategori::where('kegiatan_id', $kegiatanId)->get(); // Adjust this query based on your relationships

        return response()->json(['categories' => $categories]);
    }
    public function getKategoriKegiatan($kegiatanId)
    {
        // Ambil kategori berdasarkan kegiatan yang dipilih
        $kategori = Kategori::where('kegiatan_id', $kegiatanId)->get();

        // Kembalikan data kategori dalam bentuk JSON
        return response()->json([
            'kategori' => $kategori
        ]);
    }
    // public function getData(Request $request)
    // {
    //     $query = Proposal::with(['kegiatan', 'kategori', 'evaluation_scores', 'evaluation_scores.user'])
    //         ->when($request->kegiatan, function ($q) use ($request) {
    //             $q->where('kegiatan_id', $request->kegiatan);
    //         })
    //         ->when($request->kategori, function ($q) use ($request) {
    //             $q->where('kategori_id', $request->kategori);
    //         })
    //         ->when($request->tahun, function ($q) use ($request) {
    //             $q->whereYear('created_at', $request->tahun);
    //         });

    //     $proposals = $query->get();

    //     $data = $proposals->map(function ($proposal) {
    //         $komentar_dan_rekomendasi = $proposal->evaluation_scores->map(function ($score) {
    //             $reviewer = $score->user->name ?? 'Tidak ada';
    //             $comments = $score->comments ?? 'Tidak ada';
    //             $recommendation = $score->recommendation ?? 'Tidak ada';
    //             $skor = $score->score ?? 'Tidak ada';

    //             return "
    //             <div style='margin-bottom: 10px;'>
    //                 <b>Reviewer:</b> {$reviewer}<br>
    //                 <b>Skor:</b> {$skor}<br>
    //                 <b>Komentar:</b> {$comments}<br>
    //                 <b>Rekomendasi:</b> <span class='badge badge-danger'>{$recommendation}</span>
    //             </div>
    //             <hr>
    //         ";
    //         })->implode('');

    //         $average_score = $proposal->evaluation_scores->avg('score');

    //         return [
    //             'judul_proposal' => $proposal->judul_proposal,
    //             'namaKetua' => $proposal->namaKetua,
    //             'nimKetua' => $proposal->nimKetua,
    //             'nama_kegiatan' => $proposal->kegiatan->nama_kegiatan,
    //             'kategori_kegiatan' => $proposal->kategori->nama_kategori,
    //             'skor' => $average_score ? round($average_score, 2) : 'Belum dinilai',
    //             'komentar_dan_rekomendasi' => $komentar_dan_rekomendasi
    //         ];
    //     });

    //     return response()->json([
    //         'draw' => intval($request->draw), // untuk paging DataTables
    //         'recordsTotal' => $data->count(),
    //         'recordsFiltered' => $data->count(),
    //         'data' => $data,
    //     ]);
    // }
    // public function getData(Request $request)
    // {
    //     $query = Proposal::with(['kegiatan', 'kategori', 'evaluation_scores.user']);

    //     // Filter berdasarkan kegiatan jika dipilih
    //     if ($request->filled('nama_kegiatan')) {
    //         $query->where('kegiatan_id', $request->nama_kegiatan);
    //     }

    //     // Filter berdasarkan kategori jika dipilih
    //     if ($request->filled('kategori_kegiatan')) {
    //         $query->where('kategori_id', $request->kategori_kegiatan);
    //     }

    //     // Filter berdasarkan tahun jika dipilih
    //     if ($request->filled('tahun')) {
    //         $query->whereYear('created_at', $request->tahun);
    //     }

    //     $proposals = $query->get();

    //     $data = $proposals->map(function ($proposal) {
    //         $komentar_dan_rekomendasi = $proposal->evaluation_scores->map(function ($score) {
    //             $reviewer = $score->user->name ?? 'Tidak ada';
    //             $comments = $score->comments ?? 'Tidak ada';
    //             $recommendation = $score->recommendation ?? 'Tidak ada';
    //             $skor = $score->score ?? 'Tidak ada';

    //             return "
    //             <div style='margin-bottom: 10px;'>
    //                 <b>Reviewer:</b> {$reviewer}<br>
    //                 <b>Skor:</b> {$skor}<br>
    //                 <b>Komentar:</b> {$comments}<br>
    //                 <b>Rekomendasi:</b> <span class='badge badge-danger'>{$recommendation}</span>
    //             </div>
    //             <hr>
    //         ";
    //         })->implode('');

    //         $average_score = $proposal->evaluation_scores->avg('score');

    //         return [
    //             'judul_proposal' => $proposal->judul_proposal,
    //             'namaKetua' => $proposal->namaKetua,
    //             'nimKetua' => $proposal->nimKetua,
    //             'nama_kegiatan' => optional($proposal->kegiatan)->nama_kegiatan ?? '-',
    //             'kategori_kegiatan' => optional($proposal->kategori)->nama_kategori ?? '-',
    //             'skor' => $average_score ? round($average_score, 2) : 'Belum dinilai',
    //             'komentar_dan_rekomendasi' => $komentar_dan_rekomendasi
    //         ];
    //     });

    //     return response()->json([
    //         'draw' => intval($request->draw),
    //         'recordsTotal' => $data->count(),
    //         'recordsFiltered' => $data->count(),
    //         'data' => $data,
    //     ]);
    // }
//     public function getData(Request $request)
// {
//     $query = Proposal::with(['kegiatan', 'kategori', 'evaluation_scores.user']);

//     // Filter berdasarkan kegiatan jika dipilih
//     if ($request->filled('nama_kegiatan')) {
//         $query->where('kegiatan_id', $request->nama_kegiatan);
//     }

//     // Filter berdasarkan kategori jika dipilih
//     if ($request->filled('kategori_kegiatan')) {
//         $query->where('kategori_id', $request->kategori_kegiatan);
//     }

//     // Filter berdasarkan tahun jika dipilih
//     if ($request->filled('tahun')) {
//         $query->whereYear('created_at', $request->tahun);
//     }

//     $proposals = $query->get();

//     $data = $proposals->map(function ($proposal) {
//         // Ambil komentar dan rekomendasi dari review pertama
//         $first_score = $proposal->evaluation_scores->first();
//         $komentar_dan_rekomendasi = '';

//         if ($first_score) {
//             $reviewer = $first_score->user->name ?? 'Tidak ada';
//             $comments = $first_score->comments ?? 'Tidak ada';
//             $recommendation = $first_score->recommendation ?? 'Tidak ada';
//             $skor = $first_score->score ?? 'Tidak ada';

//             $komentar_dan_rekomendasi = "
//                 <div style='margin-bottom: 10px;'>
//                     <b>Reviewer:</b> {$reviewer}<br>
//                     <b>Skor:</b> {$skor}<br>
//                     <b>Komentar:</b> {$comments}<br>
//                     <b>Rekomendasi:</b> <span class='badge badge-danger'>{$recommendation}</span>
//                 </div>
//                 <hr>
//             ";
//         }

//         // Hitung total nilai gabungan (skor * bobot)
//         $total_nilai = $proposal->evaluation_scores->sum(function ($score) {
//             return $score->score * $score->bobot;
//         });

//         // Konversi ke skor gabungan (dalam skala 0-100)
//         $nilai_gabungan = $total_nilai ? round(($total_nilai / 1350) * 100, 2) : 'Belum dinilai';

//         return [
//             'judul_proposal' => $proposal->judul_proposal,
//             'namaKetua' => $proposal->namaKetua,
//             'nimKetua' => $proposal->nimKetua,
//             'nama_kegiatan' => optional($proposal->kegiatan)->nama_kegiatan ?? '-',
//             'kategori_kegiatan' => optional($proposal->kategori)->nama_kategori ?? '-',
//             'skor' => $nilai_gabungan,
//             'komentar_dan_rekomendasi' => $komentar_dan_rekomendasi
//         ];
//     });

//     return response()->json([
//         'draw' => intval($request->draw),
//         'recordsTotal' => $data->count(),
//         'recordsFiltered' => $data->count(),
//         'data' => $data,
//     ]);
// }

public function getData(Request $request)
{
    $query = Proposal::with([
        'evaluation_scores.penilaian',
        'evaluation_scores.reviewer',
        'kegiatan',
        'kategori'
    ]);

    if ($request->filled('nama_kegiatan')) {
        $query->where('kegiatan_id', $request->nama_kegiatan);
    }

    if ($request->filled('kategori_kegiatan')) {
        $query->where('kategori_id', $request->kategori_kegiatan);
    }

    if ($request->filled('tahun')) {
        $query->whereYear('created_at', $request->tahun);
    }

    $proposals = $query->get();

    $data = $proposals->map(function ($proposal) {
        $reviewerGrouped = $proposal->evaluation_scores->groupBy('reviewer_id');

        $reviewerInfo = '';
        $finalScores = [];

        foreach ($reviewerGrouped as $reviewer_id => $scores) {
            $totalSkorXBobot = 0;
            $totalMaxXBobot = 0;

            foreach ($scores as $score) {
                $nilai = $score->score ?? 0;
                $bobot = $score->penilaian->bobot ?? 1;
                $max_nilai = $score->penilaian->max_nilai ?? 100;

                $totalSkorXBobot += $nilai * $bobot;
                $totalMaxXBobot += $max_nilai * $bobot;
            }

            $skorReviewer = $totalMaxXBobot > 0 ? ($totalSkorXBobot / $totalMaxXBobot) * 100 : 0;
            $finalScores[] = $skorReviewer;

            $reviewerName = optional($scores->first()->reviewer)->name ?? 'Unknown';
            $komentar = $scores->first()->comments ?? '-';
            $rekomendasi = $scores->first()->recommendation ?? '-';

            $reviewerInfo .= "<b>Reviewer:</b> $reviewerName <br>";
            $reviewerInfo .= "<b>Score:</b> " . round($skorReviewer, 2) . "<br>";
            $reviewerInfo .= "<b>Komentar:</b> $komentar <br>";
            $reviewerInfo .= "<b>Rekomendasi:</b> <span class='badge badge-danger'> $rekomendasi </span><br><br>";
        }

        // Hitung rata-rata skor dari semua reviewer
        $finalScore = count($finalScores) > 0 ? array_sum($finalScores) / count($finalScores) : 0;

        return [
            'judul_proposal' => $proposal->judul_proposal,
            'namaKetua' => $proposal->namaKetua,
            'nimKetua' => $proposal->nimKetua,
            'nama_kegiatan' => $proposal->kegiatan->nama_kegiatan ?? '-',
            'kategori_kegiatan' => $proposal->kategori->nama_kategori ?? '-',
            'skor' => round($finalScore, 2),
            'komentar_dan_rekomendasi' => $reviewerInfo,
        ];
    });

    return response()->json([
        'draw' => $request->draw,
        'recordsTotal' => $data->count(),
        'recordsFiltered' => $data->count(),
        'data' => $data,
    ]);
}



    public function exportExcel(Request $request)
    {
        $namaKegiatan = 'semua_kegiatan'; // default jika tidak dipilih

        if ($request->kegiatan) {
            $kegiatan = Kegiatan::find($request->kegiatan);
            $namaKegiatan = str_replace(' ', '_', strtolower($kegiatan->nama_kegiatan));
        }

        $filename = 'rekap_' . $namaKegiatan . '.xlsx';

        return Excel::download(new RekapProposalExport($request), $filename);
    }
}

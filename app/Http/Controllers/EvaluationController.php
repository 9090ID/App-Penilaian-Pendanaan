<?php
namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\Penilaian;
use App\Models\EvaluationScore;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function create($proposal_id)
    {
        $proposal = Proposal::with('kategori')->findOrFail($proposal_id);
        if (is_string($proposal->anggota)) {
            $cleanedAnggota = str_replace(['\"', '\\'], '', $proposal->anggota);
            if ($this->isValidJson($cleanedAnggota)) {
                $proposal->anggota = json_decode($cleanedAnggota, true);
            } else {
                // If not JSON, split by commas and create an associative array
                $anggotaArray = explode(',', $cleanedAnggota);
                $proposal->anggota = [];
                foreach ($anggotaArray as $anggota) {
                    // Assuming the format is "Nama,NIM,Prodi"
                    $parts = explode(',', $anggota);
                    if (count($parts) === 3) {
                        $proposal->anggota[] = [
                            'Nama' => trim($parts[0]),
                            'NIM' => trim($parts[1]),
                            'Prodi' => trim($parts[2]),
                        ];
                    }
                }
            }
        } else {
            $proposal->anggota = [];
        }
    
        // Debugging output
        // dd($proposal->anggota); // Check the structure of anggota
    // Ambil item penilaian berdasarkan kategori proposal
    $items = Penilaian::where('kategori_id', $proposal->kategori_id)->get();

    return view('evaluations.create', compact('proposal', 'items'));
}

protected function sanitizeAnggota($anggota)
{
    return is_string($anggota) && $this->isValidJson($anggota)
        ? json_decode($anggota, true)
        : [];
}

protected function isValidJson($string)
{
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}
    public function store(Request $request)
    {
        $request->validate([
            'proposal_id' => 'required|exists:proposal,id',
            'scores' => 'required|array',
        ]);

        foreach ($request->scores as $itemId => $score) {
            EvaluationScore::create([
                'proposal_id' => $request->proposal_id,
                'reviewer_id' => auth()->id(),
                'penilaian_id' => $itemId,
                'score' => $score,
                'comments' => $request->comments,
                'recommendation' => $request->recommendation
            ]);
        }

        return redirect()->route('reviewer.index')->with('success', 'Skor berhasil disimpan.');
    }
}
<?php

namespace App\Exports;

use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

class RekapProposalExport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents
{
    protected $request;
    protected $exportData = [];
    protected $dynamicHeadings = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->prepareData(); // Process data once the request is set
    }

    // Prepare the data for the export based on query parameters
    protected function prepareData()
    {
        $query = Proposal::with(['kegiatan', 'kategori', 'evaluation_scores.reviewer', 'evaluation_scores.penilaian'])
            ->when($this->request->kegiatan, fn($q) => $q->where('kegiatan_id', $this->request->kegiatan))
            ->when($this->request->kategori, fn($q) => $q->where('kategori_id', $this->request->kategori))
            ->when($this->request->tahun, fn($q) => $q->whereYear('created_at', $this->request->tahun));

        // Fetch the data and format it for the export
        $this->exportData = $query->get()->map(function ($proposal) {
            $reviewers = DB::table('proposal_reviewer')
                ->where('proposal_id', $proposal->id)
                ->join('users', 'proposal_reviewer.user_id', '=', 'users.id')
                ->select('users.name as reviewer_name', 'proposal_reviewer.user_id')
                ->get();

            $finalScores = [];
            $reviewerData = [];

            foreach ($reviewers as $index => $reviewer) {
                $scores = $proposal->evaluation_scores->where('reviewer_id', $reviewer->user_id);
                $komentar = '-';
                $rekomendasi = '-';
                $skorReviewer = null;
                $totalSkorXBobot = 0;
                $totalMaxXBobot = 0;

                if ($scores->isNotEmpty()) {
                    $komentar = $scores->first()->comments ?? '-';
                    $rekomendasi = $scores->first()->recommendation ?? '-';

                    // Calculate total score for the reviewer
                    foreach ($scores as $score) {
                        $nilai = $score->score ?? 0;
                        $bobot = $score->penilaian->bobot ?? 1;
                        $max_nilai = $score->penilaian->max_nilai ?? 100;
                        $totalSkorXBobot += $nilai * $bobot;
                        $totalMaxXBobot += $max_nilai * $bobot;
                    }

                    // Calculate final reviewer score
                    $skorReviewer = $totalMaxXBobot > 0 ? round(($totalSkorXBobot / $totalMaxXBobot) * 100, 2) : 0;
                }

                // Add reviewer data if score is available
                if ($skorReviewer !== null) {
                    $reviewerIndex = $index + 1;
                    $reviewerData["Reviewer {$reviewerIndex} Nama"] = $reviewer->reviewer_name;
                    $reviewerData["Reviewer {$reviewerIndex} Skor"] = $skorReviewer;
                    $reviewerData["Reviewer {$reviewerIndex} Komentar"] = $komentar;
                    $reviewerData["Reviewer {$reviewerIndex} Rekomendasi"] = $rekomendasi;

                    // Store dynamic headings
                    $this->dynamicHeadings["Reviewer {$reviewerIndex} Nama"] = true;
                    $this->dynamicHeadings["Reviewer {$reviewerIndex} Skor"] = true;
                    $this->dynamicHeadings["Reviewer {$reviewerIndex} Komentar"] = true;
                    $this->dynamicHeadings["Reviewer {$reviewerIndex} Rekomendasi"] = true;

                    $finalScores[] = $skorReviewer;
                }
            }

            // Calculate overall score
            $totalSkor = count($finalScores) > 0 ? array_sum($finalScores) / count($finalScores) : 0;

            // Format final data with reviewer data
            return array_merge([
                'Judul Proposal' => $proposal->judul_proposal,
                'Nama Ketua' => $proposal->namaKetua,
                'NIM Ketua' => $proposal->nimKetua,
                'Nama Kegiatan' => $proposal->kegiatan->nama_kegiatan ?? '-',
                'Kategori Kegiatan' => $proposal->kategori->nama_kategori ?? '-',
                'Total Skor' => $totalSkor,
            ], $reviewerData);
        });
    }

    // Return the processed collection
    public function collection()
    {
        return $this->exportData;
    }

    // Return the headings, including dynamic reviewer columns
    public function headings(): array
    {
        $defaultHeaders = [
            'Judul Proposal',
            'Nama Ketua',
            'NIM Ketua',
            'Nama Kegiatan',
            'Kategori Kegiatan',
            'Total Skor',
        ];

        return array_merge($defaultHeaders, array_keys($this->dynamicHeadings));
    }

    // Set the starting cell for the export
    public function startCell(): string
    {
        return 'A3'; // Starting cell A3
    }

    // Add extra customization (like adding a title) before the sheet
    public function registerEvents(): array
{
    return [
        BeforeSheet::class => function ($event) {
            $sheet = $event->sheet;

            // Get the list of 'nama_kegiatan' from the export data
            $kegiatanList = collect($this->exportData)->pluck('Nama Kegiatan')->filter()->unique()->implode(', ');

            // Get the current year (tahun berjalan)
            $tahun = now()->year; // This will get the current year (e.g., 2025)

            // Set the cell values
            $sheet->setCellValue('A1', "Nama Kegiatan: $kegiatanList");
            $sheet->setCellValue('B1', "Tahun: $tahun");

            // Apply bold styling to the header cells
            $sheet->getStyle('A1:B1')->getFont()->setBold(true);

            // Adjust column widths to fit the content
            $sheet->getColumnDimension('A')->setAutoSize(true); // Auto-size for column A (Nama Kegiatan)
            $sheet->getColumnDimension('B')->setAutoSize(true); // Auto-size for column B (Tahun)
            $sheet->getColumnDimension('C')->setAutoSize(true); // Auto-size for column B (Tahun)
            $sheet->getColumnDimension('D')->setAutoSize(true); // Auto-size for column B (Tahun)
            $sheet->getColumnDimension('E')->setAutoSize(true); // Auto-size for column B (Tahun)
            $sheet->getColumnDimension('F')->setAutoSize(true); // Auto-size for column B (Tahun)
            $sheet->getColumnDimension('G')->setAutoSize(true); // Auto-size for column B (Tahun)
            $sheet->getColumnDimension('H')->setAutoSize(true); // Auto-size for column B (Tahun)
            $sheet->getColumnDimension('I')->setAutoSize(true); // Auto-size for column B (Tahun)
            $sheet->getColumnDimension('J')->setAutoSize(true); // Auto-size for column B (Tahun)
            $sheet->getColumnDimension('K')->setAutoSize(true); // Auto-size for column B (Tahun)
            $sheet->getColumnDimension('L')->setAutoSize(true); // Auto-size for column B (Tahun)
            $sheet->getColumnDimension('M')->setAutoSize(true); // Auto-size for column B (Tahun)
            $sheet->getColumnDimension('N')->setAutoSize(true); // Auto-size for column B (Tahun)

            // You can adjust other columns similarly if needed.
        },
    ];
}

    



}

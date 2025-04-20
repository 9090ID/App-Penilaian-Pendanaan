<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProposalExport implements FromView
{
    protected $proposals;

    // Constructor untuk menerima data proposal dari controller
    public function __construct($proposals)
    {
        $this->proposals = $proposals;
    }

    // Menggunakan view untuk mencetak data ke Excel
    public function view(): View
    {
        return view('exports.proposals', [
            'proposals' => $this->proposals
        ]);
    }
}

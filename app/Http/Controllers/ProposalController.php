<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\Kegiatan;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Imports\ProposalImport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ProposalController extends Controller
{
    public function index()
    {
        $reviewers = User::where('role', 'reviewer')->get();
        $proposals = Proposal::with(['kegiatan', 'kategori', 'reviewers'])->get(); // Ambil semua proposal dengan relasi kegiatan, kategori, dan reviewers
        return view('proposal.index', compact('proposals', 'reviewers'));
    }
    // Menampilkan form untuk menambahkan proposal
    public function create()
    {
        $kegiatan = Kegiatan::all(); // Ambil semua kegiatan
        $reviewers = User::all(); // Ambil semua pengguna sebagai reviewer
        return view('proposal.create', compact('kegiatan', 'reviewers'));
    }
    public function show($id)
    {
        abort(404); // atau return redirect()->back();
    }
    // Menyimpan proposal baru
    // public function store(Request $request)
    // {

    //     // dd($request->all());

    //     $request->validate([
    //         'kegiatan_id' => 'required|exists:kegiatan,id',
    //         'kategori_id' => 'required|exists:kategori,id',
    //         'namaKetua' => 'required|string|max:255',
    //         'nimKetua' => 'required|string|max:20',
    //         'nohpKetua' => 'required|string|max:15',
    //         'prodiKetua' => 'required|string|max:255',
    //         'fakultasKetua' => 'required|string|max:255',
    //         'judul_proposal' => 'required|string|max:255',
    //         'dosenPembimbing' => 'required|string|max:255',
    //         'nidnDosenPembimbing' => 'required|string|max:20',
    //         'anggota' => 'required|json',
    //         'linkproposal' => 'required|url',
    //         'reviewers' => 'required|array', // Validasi reviewer sebagai array
    //         'reviewers.*' => 'exists:users,id', // Validasi setiap reviewer ada di tabel users
    //     ]);
    //     //  dd($request);
    //     // Membuat proposal baru
    //     $proposal = Proposal::create([
    //         'kegiatan_id' => $request->kegiatan_id,
    //         'kategori_id' => $request->kategori_id,
    //         'namaKetua' => $request->namaKetua,
    //         'nimKetua' => $request->nimKetua,
    //         'nohpKetua' => $request->nohpKetua,
    //         'prodiKetua' => $request->prodiKetua,
    //         'fakultasKetua' => $request->fakultasKetua,
    //         'judul_proposal' => $request->judul_proposal,
    //         'dosenPembimbing' => $request->dosenPembimbing,
    //         'nidnDosenPembimbing' => $request->nidnDosenPembimbing,
    //         'anggota' => $request->anggota,
    //         'linkproposal' => $request->linkproposal,
    //     ]);

    //     // Menambahkan reviewer
    //     $proposal->reviewers()->attach($request->reviewers);

    //     return redirect()->route('proposal.index')->with('success', 'Proposal berhasil ditambahkan.');
    // }
    public function edit($id)
    {
        $proposal = Proposal::findOrFail($id);
        $kegiatan = Kegiatan::all();
        $kategoris = Kategori::where('kegiatan_id', $proposal->kegiatan_id)->get(); // Ambil kategori berdasarkan kegiatan
        $reviewers = User::where('role', 'reviewer')->get();

        return view('proposal.edit', compact('proposal', 'kegiatan', 'kategoris', 'reviewers'));
    }
    public function getProposals(Request $request)
    {
        $proposals = Proposal::with(['kegiatan', 'kategori', 'reviewers']);

        return DataTables::of($proposals)
            ->addColumn('kegiatan', fn($row) => $row->kegiatan->nama_kegiatan ?? 'N/A')
            ->addColumn('kategori', fn($row) => $row->kategori->nama_kategori ?? 'N/A')
            ->addColumn('linkproposal', fn($row) => '<a href="' . $row->linkproposal . '" target="_blank">Lihat Proposal</a>')
            ->addColumn('reviewers', function ($row) {
                if ($row->reviewers->isEmpty()) {
                    return '<span class="badge badge-secondary">Belum ada reviewer</span>';
                }
                return $row->reviewers->map(fn($r) => '<span class="badge badge-info">' . $r->name . '</span>')->implode(' ');
            })
            ->addColumn('aksi', function ($row) {
                return '<button data-toggle="modal" data-target="#editProposalModal' . $row->id . '" class="btn btn-warning btn-sm">Set Reviewer</button>';
            })
            ->rawColumns(['linkproposal', 'reviewers', 'aksi'])
            ->make(true);
    }
    public function update(Request $request, $id)
    {

        // Update proposal
        $proposal = Proposal::findOrFail($id);
        $proposal->update([
            // 'kegiatan_id' => $request->kegiatan_id,
            // 'kategori_id' => $request->kategori_id,
            // 'namaKetua' => $request->namaKetua,
            'nimKetua' => $request->nimKetua,
            // 'nohpKetua' => $request->nohpKetua,
            // 'prodiKetua' => $request->prodiKetua,
            // 'fakultasKetua' => $request->fakultasKetua,
            'judul_proposal' => $request->judul_proposal,
            // 'dosenPembimbing' => $request->dosenPembimbing,
            // 'nidnDosenPembimbing' => $request->nidnDosenPembimbing,
            // 'anggota' => $request->anggota,
            // 'linkproposal' => $request->linkproposal,
        ]);
        // dd($request)->all();
        // Update reviewer
        $proposal->reviewers()->sync($request->reviewers);

        return redirect()->route('proposal.index')->with('success', 'Proposal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $proposal = Proposal::findOrFail($id);
        $proposal->delete();
        return redirect()->route('proposal.index')->with('success', 'Proposal berhasil dihapus.');
    }
    public function getKategori($id)
    {
        $kategoris = Kategori::where('kegiatan_id', $id)->get();
        return response()->json($kategoris);
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv', // Validasi file harus Excel
        ]);

        // Import data dari file Excel
        Excel::import(new ProposalImport, $request->file('file'));

        return redirect()->route('proposal.index')->with('success', 'Data proposal berhasil diimpor.');
    }

    public function addReviewers(Request $request)
    {
        $request->validate([
            'proposal_id' => 'required|exists:proposals,id',
            'reviewer_ids' => 'required|array',
            'reviewer_ids.*' => 'exists:users,id', // Validate each reviewer ID
        ]);

        $proposal = Proposal::findOrFail($request->proposal_id);
        $proposal->reviewers()->attach($request->reviewer_ids);

        return response()->json(['message' => 'Reviewers added successfully.']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Kategori;
use App\Models\Kegiatan;
use App\Models\Proposal;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    // Menampilkan daftar item penilaian
    public function index()
    {

        $penilaians = Penilaian::with(['kategori', 'kegiatan'])->get();
        // dd($penilaians);
        return view('admin.penilaian.index', compact('penilaians'));
    }

    // Menampilkan form untuk menambah item penilaian
    public function create(Request $request)
    {
        $proposalId = $request->get('proposal_id');
        $proposal = Proposal::findOrFail($proposalId);

        return view('reviewer.create', compact('proposal'));
    }

    public function show()
    {
        $kegiatan = Kegiatan::all(); // Ambil semua kegiatan
        $kategori = Kategori::all(); // Ambil semua kategori
        return view('admin.penilaian.create', compact('kegiatan', 'kategori'));
    }

    // Menyimpan item penilaian baru
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'kategori_id' => 'required|exists:kategori,id',
    //         'kegiatan_id' => 'required|exists:kegiatan,id',
    //         'item' => 'required|string',
    //         'max_nilai' => 'required|integer|min:0',
    //         'bobot' => 'required|integer|min:0',
    //     ]);

    //     Penilaian::create($request->all());

    //     return redirect()->route('penilaian.index')->with('success', 'Item penilaian berhasil ditambahkan.');
    // }
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'kegiatan_id' => 'required|exists:kegiatan,id',
            'penilaian' => 'required|array',
            'penilaian.*.item' => 'required|string',
            'penilaian.*.bobot' => 'required|integer|min:0',
            'penilaian.*.max_nilai' => 'required|integer|min:0',
        ]);

        foreach ($request->penilaian as $penilaianData) {
            Penilaian::create([
                'kategori_id' => $request->kategori_id,
                'kegiatan_id' => $request->kegiatan_id,
                'item' => $penilaianData['item'],
                'bobot' => $penilaianData['bobot'],
                'max_nilai' => $penilaianData['max_nilai'],
            ]);
        }

        return redirect()->route('penilaian.index')->with('success', 'Semua item penilaian berhasil ditambahkan.');
    }


    // Menampilkan form untuk mengedit item penilaian
    // public function edit($id)
    // {
    //     $penilaian = Penilaian::findOrFail($id);
    //     $kategoris = Kategori::all();
    //     $kegiatans = Kegiatan::all();
    //     return view('admin.penilaian.edit', compact('penilaian', 'kategoris', 'kegiatans'));
    // }
    public function edit($kategoriId, $kegiatanId)
    {
        $kategoris = Kategori::all();
        $kegiatans = Kegiatan::all();

        $penilaians = Penilaian::where('kategori_id', $kategoriId)
            ->where('kegiatan_id', $kegiatanId)
            ->get();

        return view('admin.penilaian.edit', compact(
            'penilaians',
            'kategoris',
            'kegiatans',
            'kategoriId',
            'kegiatanId'
        ));
    }

    // Mengupdate item penilaian
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'kategori_id' => 'required|exists:kategori,id',
    //         'kegiatan_id' => 'required|exists:kegiatan,id',
    //         'item' => 'required|string',
    //         'max_nilai' => 'required|integer|min:0',
    //         'bobot' => 'required|integer|min:0',
    //     ]);

    //     $penilaian = Penilaian::findOrFail($id);
    //     $penilaian->update($request->all());

    //     return redirect()->route('penilaian.index')->with('success', 'Item penilaian berhasil diperbarui.');
    // }
    public function update(Request $request, $kategoriId, $kegiatanId)

{
    $request->validate([
        'kategori_id' => 'required|exists:kategori,id',
        'kegiatan_id' => 'required|exists:kegiatan,id',
        'penilaian' => 'required|array|min:1',
        'penilaian.*.item' => 'required|string',
        'penilaian.*.bobot' => 'required|numeric',
        'penilaian.*.max_nilai' => 'required|numeric',
    ]);

    foreach ($request->penilaian as $data) {
        // Update existing penilaian
        Penilaian::where('id', $data['id'])->update([
            'kategori_id' => $request->kategori_id,
            'kegiatan_id' => $request->kegiatan_id,
            'item' => $data['item'],
            'bobot' => $data['bobot'],
            'max_nilai' => $data['max_nilai'],
        ]);
    }

    return redirect()->route('penilaian.index')->with('success', 'Data berhasil diperbarui.');
}


    // Menghapus item penilaian
    public function destroy($id)
    {
        $penilaian = Penilaian::findOrFail($id);
        $penilaian->delete();

        return redirect()->route('penilaian.index')->with('success', 'Item penilaian berhasil dihapus.');
    }
    public function getKategoriByKegiatan($kegiatan_id)
    {
        $kategori = Kategori::where('kegiatan_id', $kegiatan_id)->get();
        return response()->json($kategori);
    }
}

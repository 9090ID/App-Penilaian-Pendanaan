<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Kategori;

class KegiatanController extends Controller
{
    // Menampilkan daftar kegiatan
    public function index()
    {
        $kegiatan = Kegiatan::with('kategoris')->paginate(10); // Ambil kegiatan dengan pagination dan kategori
        return view('kegiatan.index', compact('kegiatan'));
    }

    // Menampilkan form untuk menambahkan kegiatan
    public function create()
    {
        return view('kegiatan.create');
    }

    // Menyimpan kegiatan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|array', // Validasi kategori sebagai array
            'kategori.*.nama_kategori' => 'required|string|max:255', // Validasi nama kategori
           
        ]);

        // Membuat kegiatan baru
        $kegiatan = Kegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi' => $request->deskripsi,
        ]);

        // Menyimpan kategori yang terkait
        foreach ($request->kategori as $kategoriData) {
            $kegiatan->kategoris()->create([
                'nama_kategori' => $kategoriData['nama_kategori'],
            ]);
        }

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit kegiatan
    public function edit(Kegiatan $kegiatan)
    {
        return view('kegiatan.edit', compact('kegiatan'));
    }

    // Memperbarui kegiatan
    // public function update(Request $request, Kegiatan $kegiatan)
    // {
    //     dd($request->all());
    //     $request->validate([
    //         'nama_kegiatan' => 'required|string|max:255',
    //         'deskripsi' => 'required|string',
    //         'kategori' => 'required|array', // Validasi kategori sebagai array
    //         'kategori.*.nama_kategori' => 'required|string|max:255', // Validasi nama kategori
          
    //     ]);

    //     // Memperbarui kegiatan
    //     $kegiatan->update([
    //         'nama_kegiatan' => $request->nama_kegiatan,
    //         'deskripsi' => $request->deskripsi,
    //     ]);

    //     // Menghapus kategori yang ada
    //     $kegiatan->kategoris()->delete();

    //     // Menyimpan kategori yang baru
    //     foreach ($request->kategori as $kategoriData) {
    //         $kegiatan->kategoris()->create([
    //             'nama_kategori' => $kategoriData['nama_kategori'],
                
    //         ]);
    //     }

    //     return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diperbarui.');
    // }
    public function update(Request $request, Kegiatan $kegiatan)
{
    $request->validate([
        'nama_kegiatan' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'kategori' => 'required|array',
        'kategori.*.id' => 'nullable|integer|exists:kategori,id',
        'kategori.*.nama_kategori' => 'required|string|max:255',
    ]);

    // Update data kegiatan
    $kegiatan->update([
        'nama_kegiatan' => $request->nama_kegiatan,
        'deskripsi' => $request->deskripsi,
    ]);

    // Ambil ID kategori yang masih digunakan
    $kategoriIds = [];

    foreach ($request->kategori as $kategoriData) {
        if (!empty($kategoriData['id'])) {
            // Update kategori lama
            $kategori = $kegiatan->kategoris()->find($kategoriData['id']);
            if ($kategori) {
                $kategori->update([
                    'nama_kategori' => $kategoriData['nama_kategori']
                ]);
                $kategoriIds[] = $kategori->id;
            }
        } else {
            // Tambahkan kategori baru
            $newKategori = $kegiatan->kategoris()->create([
                'nama_kategori' => $kategoriData['nama_kategori']
            ]);
            $kategoriIds[] = $newKategori->id;
        }
    }

    // Hapus kategori yang tidak ada di input
    $kegiatan->kategoris()->whereNotIn('id', $kategoriIds)->delete();

    return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diperbarui.');
}


    // Menghapus kegiatan
    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
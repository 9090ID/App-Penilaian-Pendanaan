<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PendaftaranController;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login')->with('success', 'You have been logged out.');
})->name('logout');


Route::get('/layanan', [FrontendController::class, 'layanan'])->name('layanan');
Route::get('/', [FrontendController::class, 'index'])->name('index');
// Route::get('/daftar-kegiatan', [PendaftaranController::class, 'index'])->name('daftar');
// // Route::get('/pendaftaran/create/{kegiatanId}', [PendaftaranController::class, 'create'])->name('daftar');
// Route::post('/pendaftaran/store', [PendaftaranController::class, 'store'])->name('pendaftaran.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    // Route untuk admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::resource('users', UserController::class); // Pastikan Anda memiliki UserController
        Route::resource('kegiatan', KegiatanController::class); // Pastikan Anda memiliki KegiatanController
        Route::resource('proposal', ProposalController::class); // Pastikan Anda memiliki ProposalController
        Route::post('proposal/import', [ProposalController::class, 'import'])->name('proposal.import');
        Route::resource('penilaian', PenilaianController::class);
        Route::get('/get-kategori-by-kegiatan/{kegiatan_id}', [PenilaianController::class, 'getKategoriByKegiatan']);

        Route::get('/proposal', [ProposalController::class, 'index'])->name('proposal.index');
        Route::get('/get/datatables', [ProposalController::class, 'getProposals'])->name('get.datatables'); // yajra
        Route::post('/proposal/import', [ProposalController::class, 'import'])->name('proposal.import');
        Route::put('/proposal/{id}', [ProposalController::class, 'update'])->name('proposal.update');
        Route::get('get-data', [RekapController::class, 'getData'])->name('getData'); // Mengambil data untuk DataTables
        // Route::resource('rekap', RekapController::class);
        Route::get('penilaian/tambahNilai', [PenilaianController::class, 'tambahNilai'])->name('penilaian.tambahNilai');
        Route::get('/get-kategori/{id}', [ProposalController::class, 'getKategori']);
        Route::get('/penilaian/{kategori_id}/{kegiatan_id}/edit', [PenilaianController::class, 'edit'])->name('penilaian.edit');
        Route::put('/penilaian/{kategori}/{kegiatan}', [PenilaianController::class, 'update'])->name('penilaian.update');

        Route::post('/proposal/import', [ProposalController::class, 'import'])->name('proposal.import');
        Route::get('/rekapitulasi/data', [RekapController::class, 'getData'])->name('rekapitulasi.data');
        Route::get('/kategori/byKegiatan', [RekapController::class, 'getByKegiatan'])->name('kategori.byKegiatan');
        Route::get('/rekap/export', [RekapController::class, 'export'])->name('rekap.export');
        Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
        Route::get('/rekapitulasi', [RekapController::class, 'getFilteredData'])->name('rekapitulasi');
        // Route::get('/kegiatan', [RekapController::class, 'getKegiatan'])->name('kegiatan.list');
        Route::get('/get-categories-by-kegiatan', [RekapController::class, 'getCategoriesByKegiatan'])->name('getCategoriesByKegiatan');
        // Route::post('/addReviewers', [ProposalController::class, 'addReviewers'])->name('addReviewers');
        // Route::get('/getUsers', [ProposalController::class, 'getUsersall'])->name('getUsers');
        Route::get('/get-kategori-kegiatan/{kegiatanId}', [RekapController::class, 'getKategoriKegiatan']);
        Route::get('/rekap/export-excel', [RekapController::class, 'exportExcel'])->name('rekap.exportExcel');
        
        // Route::get('/reviewer', [ReviewerController::class, 'index'])->name('reviewer.index');
    });

    // Route untuk reviewer
    Route::middleware(['role:reviewer'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('proposal/review', [ProposalController::class, 'review'])->name('proposal.review');
        Route::get('/reviewer', [ReviewerController::class, 'index'])->name('reviewer.index');
        Route::get('/proposals', [ProposalController::class, 'index'])->name('proposals.index');
        Route::post('/reviewer/penilaian', [ReviewerController::class, 'store'])->name('reviewer.store');
        Route::get('/evaluations/create/{proposal_id}', [EvaluationController::class, 'create'])->name('evaluation.create');
        Route::post('/evaluations/store', [EvaluationController::class, 'store'])->name('evaluation.store');
        // Tambahkan route lain yang diperlukan untuk reviewer
    });
});



require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\NewsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\LayananController; 
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SejarahController;
use App\Http\Controllers\VisiMisiController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PejabatController;
use App\Http\Controllers\OPDController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\PopupController;
use App\Http\Controllers\MediaController;


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //Layanan
    Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
    Route::post('/layanan', [LayananController::class, 'store'])->name('layanan.store');
    Route::put('/layanan/{layanan}', [LayananController::class, 'update'])->name('layanan.update');
    Route::delete('/layanan/{id}', [LayananController::class, 'destroy'])->name('layanan.destroy');
    // Media
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::put('/media/{media}', [MediaController::class, 'update'])->name('media.update');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    // News
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/edit/{id}', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{id}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{id}', [NewsController::class, 'destroy'])->name('news.destroy');
    Route::get('/news/{id}', [NewsController::class, 'show'])->where('id', '[0-9]+')->name('news.show');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/upload-image', [NewsController::class, 'uploadImage']);
    Route::get('/news/{kategori}', [NewsController::class, 'showByCategory']);
    //Auth
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');
    //Popup
    Route::get('/popup', [PopupController::class, 'index'])->name('popup.index');
    Route::post('/popup', [PopupController::class, 'store'])->name('popup.store');
    Route::put('/popup/{id}', [PopupController::class, 'update'])->name('popup.update');
    Route::delete('/popup/{id}', [PopupController::class, 'destroy'])->name('popup.destroy');
    //Video
    Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
    Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
    Route::put('/videos/{id}', [VideoController::class, 'update'])->name('videos.update');
    Route::delete('/videos/{id}', [VideoController::class, 'destroy'])->name('videos.destroy');
    //Document
    Route::get('/document', [DocumentController::class, 'uploadPage'])->name('documents.upload');
    Route::post('/document', [DocumentController::class, 'store'])->name('documents.store');
    Route::put('/document/{id}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/document/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    //Pejabat
    Route::get('/local-official', [PejabatController::class, 'localOfficial'])->name('pejabat.localOfficial');
    Route::get('/local-official/create', [PejabatController::class, 'create'])->name('pejabat.create');
    Route::post('/local-official', [PejabatController::class, 'store'])->name('pejabat.store');
    Route::get('/local-official/edit/{id}', [PejabatController::class, 'edit'])->name('pejabat.edit');
    Route::put('/local-official/{id}', [PejabatController::class, 'update'])->name('pejabat.update');
    Route::delete('/local-official/{id}', [PejabatController::class, 'destroy'])->name('pejabat.destroy');
    //OPD
    Route::get('/lgo', [OPDController::class, 'lgo'])->name('opd.lgo');
    Route::get('/lgo/create', [OPDController::class, 'create'])->name('opd.create');
    Route::post('/lgo', [OPDController::class, 'store'])->name('opd.store');
    Route::get('/lgo/edit/{id}', [OPDController::class, 'edit'])->name('opd.edit');
    Route::put('/lgo/{id}', [OPDController::class, 'update'])->name('opd.update');
    Route::delete('/lgo/{id}', [OPDController::class, 'destroy'])->name('opd.destroy');
    //Kecamatan
    Route::get('/subdistrict', [KecamatanController::class, 'subdistrict'])->name('kecamatan.subdistrict');
    Route::get('/subdistrict/create', [KecamatanController::class, 'create'])->name('kecamatan.create');
    Route::post('/subdistrict', [KecamatanController::class, 'store'])->name('kecamatan.store');
    Route::get('/subdistrict/edit/{id}', [KecamatanController::class, 'edit'])->name('kecamatan.edit');
    Route::put('/subdistrict/{id}', [KecamatanController::class, 'update'])->name('kecamatan.update');
    Route::delete('/subdistrict/{id}', [KecamatanController::class, 'destroy'])->name('kecamatan.destroy');

});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/laporan_keuangan', [DocumentController::class, 'index'])->name('Laporan_Keuangan.index');
Route::get('/laporan-keuangan', [DocumentController::class, 'LaporanKeuangan'])->name('documents.laporanKeuangan');
Route::get('/unduh/{id}', [DocumentController::class, 'download'])->name('Laporan_Keuangan.download'); 
Route::get('/PejabatDaerah', [PejabatController::class, 'index'])->name('PejabatDaerah.index');
Route::get('/OPD', [OPDController::class, 'index'])->name('OPD.index');
Route::get('/Kecamatan', [KecamatanController::class, 'index'])->name('Kecamatan.index');
Route::get('/LambangDaerah', [PageController::class, 'LambangDaerah'])->name('LambangDaerah');
Route::get('/VisiMisi', [VisiMisiController::class, 'index'])->name('VisiMisi');
Route::get('/Sejarah', [SejarahController::class, 'index'])->name('Sejarah');
Route::get('/BeritaPemerintah', [PageController::class, 'BeritaPemerintah'])->name('BeritaPemerintah');
Route::get('/BeritaDaerah', [PageController::class, 'BeritaDaerah'])->name('BeritaDaerah');
Route::get('/DataStatistik', [DocumentController::class, 'DataStatistik'])->name('DataStatistik');
Route::get('/PeraturanBupati', [DocumentController::class, 'PeraturanBupati'])->name('PeraturanBupati');
Route::get('/PPID', [PageController::class, 'PPID'])->name('PPID');
Route::get('/Kontak', [PageController::class, 'Kontak'])->name('Kontak');
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{id}', [BeritaController::class, 'show'])->where('id', '[0-9]+')->name('berita.show');
Route::get('/berita/kategori/{kategori}', [BeritaController::class, 'showByCategory'])->name('berita.category');

require __DIR__.'/auth.php';
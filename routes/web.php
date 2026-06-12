<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\PendaftaranController;
use App\Http\Controllers\User\StatusController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\IndividuController;
use App\Http\Controllers\Admin\KelompokController as AdminKelompokController;
use App\Http\Controllers\Admin\PesertaController;
use App\Http\Controllers\Admin\StatistikController;

Route::get('/', [HomeController::class, 'index'])->name('user.home');

Route::get('/pendaftaran/pilih-tipe', [HomeController::class, 'pilihTipe'])->name('user.pilih-tipe');
Route::post('/pendaftaran/pilih-tipe', [HomeController::class, 'prosesPilihTipe'])->name('user.proses-pilih-tipe');

Route::post('/generate-kode-individu', [PendaftaranController::class, 'generateKodeIndividu'])->name('user.generate.kode.individu');
Route::post('/generate-kode-kelompok', [PendaftaranController::class, 'generateKodeKelompok'])->name('user.generate.kode.kelompok');

Route::get('/pendaftaran/individu', [PendaftaranController::class, 'showRegistrationForm'])->name('user.register.individu');
Route::post('/pendaftaran/individu', [PendaftaranController::class, 'storeRegistration'])->name('user.register.individu.store');

Route::get('/pendaftaran/kelompok', [PendaftaranController::class, 'formKelompok'])->name('user.kelompok.create');
Route::post('/pendaftaran/kelompok', [PendaftaranController::class, 'storeKelompok'])->name('user.kelompok.store');
Route::get('/pendaftaran/kelompok/{id}/edit', [PendaftaranController::class, 'editKelompok'])->name('user.kelompok.edit');
Route::put('/pendaftaran/kelompok/{id}', [PendaftaranController::class, 'updateKelompok'])->name('user.kelompok.update');
Route::get('/pendaftaran/kelompok/{kelompokId}/tambah-peserta', [PendaftaranController::class, 'tambahPesertaKelompok'])->name('user.kelompok.tambah-peserta');
Route::post('/pendaftaran/kelompok/simpan-peserta', [PendaftaranController::class, 'simpanPesertaKelompok'])->name('user.kelompok.simpan-peserta');
Route::put('/pendaftaran/kelompok/update-anggota', [PendaftaranController::class, 'updateAnggota'])->name('user.kelompok.update-anggota');
Route::delete('/pendaftaran/kelompok/anggota/{id}', [PendaftaranController::class, 'hapusAnggota'])->name('user.kelompok.hapus-anggota');
Route::post('/pendaftaran/kelompok/{id}/final-submit', [PendaftaranController::class, 'finalSubmitKelompok'])->name('user.kelompok.final-submit');

Route::get('/status', [StatusController::class, 'showStatusForm'])->name('user.status');
Route::post('/status/check', [StatusController::class, 'checkStatus'])->name('user.status.check');
Route::get('/status/detail/{id}', [StatusController::class, 'showRegistrationDetail'])->name('user.registration.detail');
Route::post('/get-kode-by-wa', [StatusController::class, 'getKodeByWa'])->name('user.get-kode-by-wa');

Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/chart-data', [DashboardController::class, 'getChartDataApi'])->name('chart.data');
    
    Route::get('/individu/pending-counts', [IndividuController::class, 'getPendingCounts'])->name('individu.pending-counts');
    Route::get('/kelompok/pending-counts', [AdminKelompokController::class, 'getPendingCounts'])->name('kelompok.pending-counts');
    
    Route::get('/individu/counts', [IndividuController::class, 'getCounts'])->name('individu.counts');
    Route::get('/kelompok/counts', [AdminKelompokController::class, 'getCounts'])->name('kelompok.counts');

    Route::prefix('individu')->name('individu.')->group(function () {
        Route::get('/', [IndividuController::class, 'index'])->name('index');
        Route::get('/export', [IndividuController::class, 'export'])->name('export');
        Route::get('/{id}', [IndividuController::class, 'show'])->name('show');
        Route::put('/{id}/status', [IndividuController::class, 'updateStatus'])->name('status.update');
        Route::delete('/{id}', [IndividuController::class, 'destroy'])->name('destroy');
        Route::post('/mass-destroy', [IndividuController::class, 'massDestroy'])->name('mass-destroy');
        Route::get('/{id}/download-surat', [IndividuController::class, 'downloadSurat'])->name('download-surat');
        Route::get('/{id}/download-cv', [IndividuController::class, 'downloadCv'])->name('download-cv');
        Route::get('/{id}/view-surat', [IndividuController::class, 'viewSurat'])->name('view-surat');
        Route::get('/{id}/export', [IndividuController::class, 'exportSingle'])->name('export-single');
    });

    Route::prefix('kelompok')->name('kelompok.')->group(function () {
        Route::get('/', [AdminKelompokController::class, 'index'])->name('index');
        Route::get('/export', [AdminKelompokController::class, 'export'])->name('export');
        Route::post('/mass-destroy', [AdminKelompokController::class, 'massDestroy'])->name('mass-destroy');
        Route::get('/{id}', [AdminKelompokController::class, 'show'])->name('show');
        Route::put('/{id}/status', [AdminKelompokController::class, 'updateStatus'])->name('status.update');
        Route::delete('/{id}', [AdminKelompokController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/download-surat', [AdminKelompokController::class, 'downloadSurat'])->name('download-surat');
        Route::get('/{id}/download-cv', [AdminKelompokController::class, 'downloadCv'])->name('download-cv');
        Route::get('/{id}/view-surat', [AdminKelompokController::class, 'viewSurat'])->name('view-surat');
        Route::get('/{id}/export', [AdminKelompokController::class, 'exportSingle'])->name('export-single');
    });

    Route::get('/peserta', [PesertaController::class, 'index'])->name('peserta.index');

    Route::prefix('statistik')->name('statistik.')->group(function () {
        Route::get('/', [StatistikController::class, 'index'])->name('index');
        Route::get('/data', [StatistikController::class, 'getStatistics'])->name('data');
        Route::get('/export/excel', [StatistikController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/csv', [StatistikController::class, 'exportCsv'])->name('export.csv');
        Route::get('/debug', [StatistikController::class, 'debugStats'])->name('debug');
        Route::post('/fix-created-at', [StatistikController::class, 'fixCreatedAt'])->name('fix-created-at');
        Route::get('/monthly', [StatistikController::class, 'getMonthlyStats'])->name('monthly');
        Route::get('/kelompok/{id}', [StatistikController::class, 'getKelompokDetail'])->name('kelompok-detail');
    });
    
    Route::get('/export', [StatistikController::class, 'export'])->name('export');
});

Route::get('/uploads/surat_pengantar/{filename}', function ($filename) {
    $path = public_path('uploads/surat_pengantar/' . $filename);
    if (!file_exists($path)) abort(404);
    return response()->file($path);
})->where('filename', '.*')->name('view.surat');

Route::get('/uploads/cv/{filename}', function ($filename) {
    $path = public_path('uploads/cv/' . $filename);
    if (!file_exists($path)) {
        $altPath = public_path('uploads/cv_ind/' . $filename);
        if (file_exists($altPath)) {
            return response()->file($altPath);
        }
        abort(404);
    }
    return response()->file($path);
})->where('filename', '.*')->name('view.cv');

Route::get('/favicon.ico', function () {
    return response('', 204);
});

Route::fallback(function () {
    return redirect()->route('user.home');
});
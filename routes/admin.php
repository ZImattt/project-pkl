<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\IndividuController;
use App\Http\Controllers\Admin\KelompokController;
use App\Http\Controllers\Admin\PesertaController;
use App\Http\Controllers\Admin\StatistikController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');

Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::get('/individu', [IndividuController::class, 'index'])->name('admin.individu.index');
    Route::get('/individu/{id}', [IndividuController::class, 'show'])->name('admin.individu.show');
    Route::put('/individu/{id}/status', [IndividuController::class, 'updateStatus'])->name('admin.individu.status.update');
    Route::delete('/individu/{id}', [IndividuController::class, 'destroy'])->name('admin.individu.destroy');
    Route::post('/individu/mass-destroy', [IndividuController::class, 'massDestroy'])->name('admin.individu.mass-destroy');
    Route::get('/individu/{id}/download-surat', [IndividuController::class, 'downloadSurat'])->name('admin.individu.download-surat');
    Route::get('/individu/{id}/view-surat', [IndividuController::class, 'viewSurat'])->name('admin.individu.view-surat');

    Route::get('/kelompok', [KelompokController::class, 'index'])->name('admin.kelompok.index');
    Route::get('/kelompok/export', [KelompokController::class, 'export'])->name('admin.kelompok.export');
    Route::post('/kelompok/mass-destroy', [KelompokController::class, 'massDestroy'])->name('admin.kelompok.mass-destroy');
    Route::get('/kelompok/{id}', [KelompokController::class, 'show'])->name('admin.kelompok.show');
    Route::put('/kelompok/{id}/status', [KelompokController::class, 'updateStatus'])->name('admin.kelompok.status.update');
    Route::delete('/kelompok/{id}', [KelompokController::class, 'destroy'])->name('admin.kelompok.destroy');
    Route::get('/kelompok/{id}/download-surat', [KelompokController::class, 'downloadSurat'])->name('admin.kelompok.download-surat');
    Route::get('/kelompok/{id}/view-surat', [KelompokController::class, 'viewSurat'])->name('admin.kelompok.view-surat');
    Route::get('/kelompok/{id}/download-cv', [KelompokController::class, 'downloadCv'])->name('admin.kelompok.download-cv');

    Route::get('/peserta', [PesertaController::class, 'index'])->name('admin.peserta.index');

    Route::get('/statistik', [StatistikController::class, 'index'])->name('admin.statistik.index');
    Route::get('/statistik/refresh', [StatistikController::class, 'getStatistics'])->name('admin.statistik.refresh');
    Route::get('/export', [StatistikController::class, 'export'])->name('admin.export');
    Route::get('/export/excel', [StatistikController::class, 'exportExcel'])->name('admin.export.excel');
    Route::get('/export/csv', [StatistikController::class, 'exportCsv'])->name('admin.export.csv');
});
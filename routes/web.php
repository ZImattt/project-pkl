<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;

Route::get('/', [StudentController::class, 'home'])->name('student.home');
Route::get('/pendaftaran', [StudentController::class, 'showRegistrationForm'])->name('student.register');
Route::post('/pendaftaran', [StudentController::class, 'storeRegistration'])->name('student.register.store');
Route::get('/status', [StudentController::class, 'showStatusForm'])->name('student.status');
Route::post('/status/check', [StudentController::class, 'checkStatus'])->name('student.status.check');
Route::get('/status/detail/{id}', [StudentController::class, 'showRegistrationDetail'])->name('student.registration.detail');

Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/export', [AdminController::class, 'export'])->name('export');
    Route::get('/export/csv', [AdminController::class, 'exportCsv'])->name('export.csv');
    
    Route::get('/peserta-pkl', [AdminController::class, 'pesertaPkl'])->name('peserta.pkl');
    
    Route::get('/registrations', [AdminController::class, 'registrations'])->name('registrations.index');
    Route::get('/registrations/{id}', [AdminController::class, 'showRegistration'])->name('registrations.show');
    
    Route::put('/registrations/{id}', [AdminController::class, 'update'])->name('registrations.update');
    Route::put('/registrations/{id}/status', [AdminController::class, 'updateStatus'])->name('registrations.status.update');
    Route::delete('/registrations/{id}', [AdminController::class, 'destroy'])->name('registrations.destroy');
    Route::get('/registrations/{id}/download-surat', [AdminController::class, 'downloadSurat'])->name('download.surat');
    
    Route::post('/registrations/{id}/approve', [AdminController::class, 'approve'])->name('registrations.approve');
    Route::post('/registrations/{id}/reject', [AdminController::class, 'reject'])->name('registrations.reject');
    
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});

Route::get('/uploads/surat_pengantar/{filename}', function($filename) {
    $path = public_path('uploads/surat_pengantar/' . $filename);
    if (!file_exists($path)) abort(404);
    return response()->file($path);
})->where('filename', '.*')->name('view.surat');

Route::fallback(function () {
    return view('errors.404');
});
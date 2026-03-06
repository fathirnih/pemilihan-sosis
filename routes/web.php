<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\PemilihController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\KandidatController;
use App\Http\Controllers\SuaraController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\PanitiaUserController;

// Public routes - Voter
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Protected voting routes - Voter
Route::middleware('pemilih')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/voting', [VotingController::class, 'index'])->name('voting.index');
    Route::get('/voting/status', [VotingController::class, 'status'])->name('voting.status');
    Route::post('/voting', [VotingController::class, 'store'])->name('voting.store');
    Route::get('/results', [ResultsController::class, 'index'])->name('results.index');
});

// Public routes - Staff (Admin + Panitia)
Route::get('/admin/login', [StaffAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [StaffAuthController::class, 'login'])->name('admin.login.submit');

// Backward-compatible login entry points

// Protected admin routes
Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/generate-token', [AdminController::class, 'showGenerateToken'])->name('admin.show-generate-token');
    Route::post('/admin/generate-token', [AdminController::class, 'generateToken'])->name('admin.generate-token');
    Route::get('/admin/manage-periode', function () {
        return redirect()->route('admin.periode.index');
    })->name('admin.manage-periode');
    Route::post('/admin/toggle-periode/{id}', [PeriodeController::class, 'toggleStatus'])->name('admin.toggle-periode');
    
    // Pemilih CRUD routes
    Route::get('/admin/pemilih', [PemilihController::class, 'index'])->name('admin.pemilih.index');
    Route::get('/admin/pemilih/create', [PemilihController::class, 'create'])->name('admin.pemilih.create');
    Route::post('/admin/pemilih', [PemilihController::class, 'store'])->name('admin.pemilih.store');
    Route::get('/admin/pemilih/{id}/edit', [PemilihController::class, 'edit'])->name('admin.pemilih.edit');
    Route::put('/admin/pemilih/{id}', [PemilihController::class, 'update'])->name('admin.pemilih.update');
    Route::delete('/admin/pemilih/{id}', [PemilihController::class, 'destroy'])->name('admin.pemilih.destroy');
    Route::get('/admin/pemilih/print-tokens', [PemilihController::class, 'printTokens'])->name('admin.pemilih.print-tokens');
    Route::post('/admin/pemilih/generate-token', [PemilihController::class, 'generateTokens'])->name('admin.pemilih.generate-token');
    Route::post('/admin/pemilih/hapus-token-semua', [PemilihController::class, 'deleteAllTokens'])->name('admin.pemilih.hapus-token-semua');
    Route::post('/admin/pemilih/{id}/reset-token', [PemilihController::class, 'resetToken'])->name('admin.pemilih.reset-token');

    // Token print/pdf routes (keep)
    Route::get('/admin/tokens/{id}/print', [TokenController::class, 'print'])->name('admin.tokens.print');
    Route::get('/admin/tokens/{id}/pdf', [TokenController::class, 'downloadPdf'])->name('admin.tokens.downloadPdf');

    // Periode CRUD routes
    Route::get('/admin/periode', [PeriodeController::class, 'index'])->name('admin.periode.index');
    Route::get('/admin/periode/create', [PeriodeController::class, 'create'])->name('admin.periode.create');
    Route::post('/admin/periode', [PeriodeController::class, 'store'])->name('admin.periode.store');
    Route::get('/admin/periode/{id}/edit', [PeriodeController::class, 'edit'])->name('admin.periode.edit');
    Route::put('/admin/periode/{id}', [PeriodeController::class, 'update'])->name('admin.periode.update');
    Route::delete('/admin/periode/{id}', [PeriodeController::class, 'destroy'])->name('admin.periode.destroy');

    // Kandidat CRUD routes
    Route::get('/admin/kandidat', [KandidatController::class, 'index'])->name('admin.kandidat.index');
    Route::get('/admin/kandidat/create', [KandidatController::class, 'create'])->name('admin.kandidat.create');
    Route::post('/admin/kandidat', [KandidatController::class, 'store'])->name('admin.kandidat.store');
    Route::get('/admin/kandidat/{id}/edit', [KandidatController::class, 'edit'])->name('admin.kandidat.edit');
    Route::put('/admin/kandidat/{id}', [KandidatController::class, 'update'])->name('admin.kandidat.update');
    Route::delete('/admin/kandidat/{id}', [KandidatController::class, 'destroy'])->name('admin.kandidat.destroy');

    // Suara CRUD routes
    Route::get('/admin/suara', [SuaraController::class, 'index'])->name('admin.suara.index');
    Route::get('/admin/suara/create', [SuaraController::class, 'create'])->name('admin.suara.create');
    Route::post('/admin/suara', [SuaraController::class, 'store'])->name('admin.suara.store');
    Route::get('/admin/suara/{id}/edit', [SuaraController::class, 'edit'])->name('admin.suara.edit');
    Route::put('/admin/suara/{id}', [SuaraController::class, 'update'])->name('admin.suara.update');
    Route::delete('/admin/suara/{id}', [SuaraController::class, 'destroy'])->name('admin.suara.destroy');

    // Admin CRUD routes
    Route::get('/admin/admins', [AdminUserController::class, 'index'])->name('admin.admins.index');
    Route::get('/admin/admins/create', [AdminUserController::class, 'create'])->name('admin.admins.create');
    Route::post('/admin/admins', [AdminUserController::class, 'store'])->name('admin.admins.store');
    Route::get('/admin/admins/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.admins.edit');
    Route::put('/admin/admins/{id}', [AdminUserController::class, 'update'])->name('admin.admins.update');
    Route::delete('/admin/admins/{id}', [AdminUserController::class, 'destroy'])->name('admin.admins.destroy');

    // Panitia CRUD routes
    Route::get('/admin/panitia', [PanitiaUserController::class, 'index'])->name('admin.panitia.index');
    Route::get('/admin/panitia/create', [PanitiaUserController::class, 'create'])->name('admin.panitia.create');
    Route::post('/admin/panitia', [PanitiaUserController::class, 'store'])->name('admin.panitia.store');
    Route::get('/admin/panitia/{id}/edit', [PanitiaUserController::class, 'edit'])->name('admin.panitia.edit');
    Route::put('/admin/panitia/{id}', [PanitiaUserController::class, 'update'])->name('admin.panitia.update');
    Route::delete('/admin/panitia/{id}', [PanitiaUserController::class, 'destroy'])->name('admin.panitia.destroy');

    // Kelas CRUD routes
    Route::get('/admin/kelas', [KelasController::class, 'index'])->name('admin.kelas.index');
    Route::get('/admin/kelas/create', [KelasController::class, 'create'])->name('admin.kelas.create');
    Route::post('/admin/kelas', [KelasController::class, 'store'])->name('admin.kelas.store');
    Route::get('/admin/kelas/{id}/edit', [KelasController::class, 'edit'])->name('admin.kelas.edit');
    Route::put('/admin/kelas/{id}', [KelasController::class, 'update'])->name('admin.kelas.update');
    Route::delete('/admin/kelas/{id}', [KelasController::class, 'destroy'])->name('admin.kelas.destroy');
    
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
});

// Protected panitia routes
Route::middleware('panitia')->group(function () {
    Route::get('/panitia/results', [PanitiaController::class, 'viewResults'])->name('panitia.results');
    Route::post('/panitia/logout', [PanitiaController::class, 'logout'])->name('panitia.logout');
});

// Default route - Landing page
Route::get('/', function () {
    return view('welcome-new');
});

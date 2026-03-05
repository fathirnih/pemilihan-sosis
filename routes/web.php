<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\KelasController;

// Public routes - Voter
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Protected voting routes - Voter
Route::middleware('pemilih')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/voting', [VotingController::class, 'index'])->name('voting.index');
    Route::post('/voting', [VotingController::class, 'store'])->name('voting.store');
    Route::get('/results', [ResultsController::class, 'index'])->name('results.index');
});

// Public routes - Admin
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login']);

// Protected admin routes
Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/generate-token', [AdminController::class, 'showGenerateToken'])->name('admin.show-generate-token');
    Route::post('/admin/generate-token', [AdminController::class, 'generateToken'])->name('admin.generate-token');
    Route::get('/admin/manage-periode', [AdminController::class, 'managePeriode'])->name('admin.manage-periode');
    Route::post('/admin/toggle-periode/{id}', [AdminController::class, 'togglePeriode'])->name('admin.toggle-periode');
    
    // Token CRUD routes
    Route::get('/admin/tokens', [TokenController::class, 'index'])->name('admin.tokens.index');
    Route::get('/admin/tokens/create', [TokenController::class, 'create'])->name('admin.tokens.create');
    Route::post('/admin/tokens', [TokenController::class, 'store'])->name('admin.tokens.store');
    Route::get('/admin/tokens/{id}/edit', [TokenController::class, 'edit'])->name('admin.tokens.edit');
    Route::put('/admin/tokens/{id}', [TokenController::class, 'update'])->name('admin.tokens.update');
    Route::get('/admin/tokens/{id}/print', [TokenController::class, 'print'])->name('admin.tokens.print');
    Route::get('/admin/tokens/{id}/pdf', [TokenController::class, 'downloadPdf'])->name('admin.tokens.downloadPdf');
    Route::delete('/admin/tokens/{id}', [TokenController::class, 'destroy'])->name('admin.tokens.destroy');

    // Kelas CRUD routes
    Route::get('/admin/kelas', [KelasController::class, 'index'])->name('admin.kelas.index');
    Route::get('/admin/kelas/create', [KelasController::class, 'create'])->name('admin.kelas.create');
    Route::post('/admin/kelas', [KelasController::class, 'store'])->name('admin.kelas.store');
    Route::get('/admin/kelas/{id}/edit', [KelasController::class, 'edit'])->name('admin.kelas.edit');
    Route::put('/admin/kelas/{id}', [KelasController::class, 'update'])->name('admin.kelas.update');
    Route::delete('/admin/kelas/{id}', [KelasController::class, 'destroy'])->name('admin.kelas.destroy');
    
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
});

// Public routes - Panitia
Route::get('/panitia/login', [PanitiaController::class, 'showLogin'])->name('panitia.login');
Route::post('/panitia/login', [PanitiaController::class, 'login']);

// Protected panitia routes
Route::middleware('panitia')->group(function () {
    Route::get('/panitia/results', [PanitiaController::class, 'viewResults'])->name('panitia.results');
    Route::post('/panitia/logout', [PanitiaController::class, 'logout'])->name('panitia.logout');
});

// Default route - Landing page
Route::get('/', function () {
    return view('welcome-new');
});

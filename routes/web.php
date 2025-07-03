<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\HistoriController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/proses_login', [AuthController::class, 'proses_login'])->name('proses_login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Obat
    Route::get('/dataobat', [ObatController::class, 'index'])->name('obat.index');
    Route::post('/dataobat', [ObatController::class, 'store'])->name('obat.store');
    Route::put('/dataobat/{id_obat}', [ObatController::class, 'update'])->name('obat.update');
    Route::delete('/dataobat/{id_obat}', [ObatController::class, 'destroy'])->name('obat.destroy');
    Route::get('/dataobat/{id_obat}', [ObatController::class, 'edit'])->name('obat.edit');

    // Data Pemakaian
    Route::get('/datapersediaan', [PemakaianController::class, 'index'])->name('pemakaian.index');
    Route::post('/datapersediaan', [PemakaianController::class, 'store'])->name('pemakaian.store');
    Route::put('/datapersediaan/{id_pemakaian}', [PemakaianController::class, 'update'])->name('pemakaian.update');
    Route::delete('/datapersediaan/{id_pemakaian}', [PemakaianController::class, 'destroy'])->name('pemakaian.destroy');
    Route::get('/datapersediaan/{id_pemakaian}', [PemakaianController::class, 'edit'])->name('pemakaian.edit');

    // Prediksi
    Route::get('/prediksi', [PrediksiController::class, 'index'])->name('prediksi');
    Route::post('/prediksi/store', [PrediksiController::class, 'storeJob'])->name('prediksi.store');

    // Histori Prediksi
    Route::get('/histori', [HistoriController::class, 'index'])->name('histori');
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {

    Route::get('/datauser', [UserController::class, 'index'])->name('user.index');
    Route::post('/datauser', [UserController::class, 'store'])->name('user.store');
    Route::put('/datauser/{id_user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/datauser/{id_user}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/datauser/{id_user}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/datauser/update-password/{id_user}', [UserController::class, 'updatePassword'])->name('user.updatePassword');
});

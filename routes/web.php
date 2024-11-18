<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\DatapengirimController;
use App\Http\Controllers\DatapengukuranController;


Route::get('/', function () {
    return redirect()->route('login'); // Mengarahkan user ke halaman login
})->middleware('guest'); // Hanya jika user belum login

// Akses Home untuk user yang sudah login
Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::middleware('auth')->group(function () {
    
    Route::get('/Data', [DataController::class, 'index'])->name('data.index');
    Route::get('/Data/{nama_observant}', [DataController::class, 'index'])->name('data.showpengukuran');
    Route::get('/HasilView/{namadata}', [DataController::class, 'showHasil'])->name('data.Hasil');
    
    Route::get('/Data', [DatapengirimController::class, 'index'])->name('datapengirim.index');
    Route::get('/Data/CreatePengguna', [DatapengirimController::class, 'create'])->name('datapengirim.create');
    Route::post('/Data/CreatePengguna', [DatapengirimController::class, 'store'])->name('datapengirim.store');
    Route::put('/Data/{id}', [DatapengirimController::class, 'update'])->name('datapengirim.update');
    Route::delete('/Data/{id}', [DatapengirimController::class, 'destroy'])->name('datapengirim.destroy');
    Route::get('/KelompokSupir', [DatapengirimController::class, 'groupAnalysis'])->name('datapengirim.groupAnalysis');
    
    Route::get('/Data/{nama_observant}', [DatapengukuranController::class, 'index'])->name('datapengukuran.index'); 
    Route::get('/Data/analisiskelompok/{nama_observant}', [DatapengukuranController::class, 'analyze'])->name('datapengukuran.analyze');

    Route::get('/profile', [UserController::class, 'show'])->name('profile.show');
    Route::resource('user', UserController::class);

});

Route::get('/login', [LoginController::class, 'index'] )->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'] );
Route::post('/logout', [LoginController::class, 'logout'] );

Route::get('/register', [RegisterController::class, 'index'] )->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'] );

Route::get('/verify', [RegisterController::class, 'showVerifyForm'])->name('verify.show');



Route::get('/TambahPerusahaan', [PerusahaanController::class, 'create']);

Route::middleware(['auth'])->group(function () {
    Route::get('/Management', [UserController::class, 'showRoleManagement'])->name('role.management');
    Route::post('/Management/assign-role/{userId}', [UserController::class, 'assignRole'])->name('assign.role');
});

Route::middleware(['auth', 'owner'])->group(function(){
    Route::get('/perusahaan', [PerusahaanController::class, 'index'])->name('perusahaan.index');
    Route::post('/perusahaan', [PerusahaanController::class, 'store'])->name('perusahaan.store');
});




<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\TransaksiController; 

/*
|--------------------------------------------------------------------------
| RUTE REDIRECT BAWAAN (/home)
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect('/admin/dashboard');
    }
    return redirect('/');
});

/*
|--------------------------------------------------------------------------
| 1. HALAMAN PUBLIK (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/
// Menggunakan PageController
Route::get('/', [\App\Http\Controllers\User\PageController::class, 'beranda'])->name('home');
Route::get('/layanan', [\App\Http\Controllers\User\PageController::class, 'layanan'])->name('layanan.index');
Route::get('/tentang-kami', [\App\Http\Controllers\User\PageController::class, 'tentangKami'])->name('tentang-kami.index');

// Cek Tiket
Route::get('/cek-tiket', [\App\Http\Controllers\User\CekTiketController::class, 'index'])->name('cek-tiket.index');


/*
|--------------------------------------------------------------------------
| 2. SISTEM AUTENTIKASI
|--------------------------------------------------------------------------
*/
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 3. AREA PRIVAT PELANGGAN (Wajib Login & Role: pelanggan)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    
    // Kelola Akun Pelanggan
    Route::prefix('akun')->group(function () {
        Route::get('/pusat-akun', function () { return view('user.akun.pusat-akun'); })->name('akun.pusat');
        Route::post('/pusat-akun/update', [ProfileController::class, 'updateUser'])->name('user.profil.update');
        Route::post('/pusat-akun/password', [ProfileController::class, 'updatePassword'])->name('user.password.update');
        Route::delete('/pusat-akun/hapus', [ProfileController::class, 'destroyUser'])->name('user.akun.hapus');
    });

    // Proses Transaksi Pesanan (Dari form di UI Layanan)
    Route::post('/pesan/travel', [\App\Http\Controllers\TransaksiController::class, 'storeTravel'])->name('pesan.travel');
    Route::post('/pesan/kargo', [\App\Http\Controllers\TransaksiController::class, 'storeKargo'])->name('pesan.kargo');

    // Kelola Pesanan (Cek Tiket)
    Route::post('/cek-tiket/upload-bukti', [\App\Http\Controllers\User\CekTiketController::class, 'uploadBukti'])->name('user.pesanan.upload');
    Route::delete('/cek-tiket/batal/{type}/{id}', [\App\Http\Controllers\User\CekTiketController::class, 'batalkan'])->name('user.pesanan.batal');
});

/*
|--------------------------------------------------------------------------
| 4. AREA PRIVAT ADMIN (Wajib Login & Role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    
    // Tampilan Dashboard & Menu Utama Admin
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Jadwal Operasional
    Route::get('/jadwal', [\App\Http\Controllers\Admin\JadwalController::class, 'index'])->name('admin.jadwal');
    Route::post('/jadwal/tambah', [\App\Http\Controllers\Admin\JadwalController::class, 'store'])->name('admin.jadwal.store');
    Route::put('/jadwal/{id}', [\App\Http\Controllers\Admin\JadwalController::class, 'update'])->name('admin.jadwal.update');
    Route::delete('/jadwal/{id}', [\App\Http\Controllers\Admin\JadwalController::class, 'destroy'])->name('admin.jadwal.destroy');
    
    // Kelola Data Pelanggan & KTM
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('admin.pelanggan');
    Route::post('/pelanggan/{id}/terima', [PelangganController::class, 'terimaKtm'])->name('admin.pelanggan.terima');
    Route::post('/pelanggan/{id}/tolak', [PelangganController::class, 'tolakKtm'])->name('admin.pelanggan.tolak');

    // --- TAMBAHAN UNTUK RESET & HAPUS AKUN ---
    Route::put('/pelanggan/{id}/reset-password', [PelangganController::class, 'resetPassword'])->name('admin.pelanggan.reset');
    Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('admin.pelanggan.destroy');
    
    // Kelola Profil Admin
    Route::get('/profil', function () { return view('admin.profil.index'); })->name('admin.profil');
    Route::post('/profil/update', [ProfileController::class, 'updateAdmin'])->name('admin.profil.update');
    Route::post('/profil/password', [ProfileController::class, 'updatePassword'])->name('admin.password.update');

    // Kelola Armada & Rute
    Route::get('/data-master', [\App\Http\Controllers\Admin\ArmadaRuteController::class, 'index'])->name('admin.data_master');
    Route::put('/rute/{id}', [\App\Http\Controllers\Admin\ArmadaRuteController::class, 'updateRute'])->name('admin.rute.update');
    Route::put('/armada/{id}', [\App\Http\Controllers\Admin\ArmadaRuteController::class, 'updateArmada'])->name('admin.armada.update');

    Route::post('/rute', [\App\Http\Controllers\Admin\ArmadaRuteController::class, 'storeRute'])->name('admin.rute.store');
    Route::delete('/rute/{id}', [\App\Http\Controllers\Admin\ArmadaRuteController::class, 'destroyRute'])->name('admin.rute.destroy');
    Route::post('/armada', [\App\Http\Controllers\Admin\ArmadaRuteController::class, 'storeArmada'])->name('admin.armada.store');

    // Pesanan Masuk & Manifest
    Route::get('/pesanan', [\App\Http\Controllers\Admin\PesananMasukController::class, 'index'])->name('admin.pesanan');
    Route::put('/pesanan/travel/{id}', [\App\Http\Controllers\Admin\PesananMasukController::class, 'updateTravel'])->name('admin.pesanan.travel.update');
    Route::put('/pesanan/kargo/{id}', [\App\Http\Controllers\Admin\PesananMasukController::class, 'updateKargo'])->name('admin.pesanan.kargo.update');
    Route::put('/kargo/{id}/assign', [\App\Http\Controllers\Admin\PesananMasukController::class, 'assignKargo'])->name('admin.kargo.assign');
    Route::delete('/pesanan/travel/{id}', [\App\Http\Controllers\Admin\PesananMasukController::class, 'destroyTravel'])->name('admin.pesanan.travel.destroy');
    Route::delete('/pesanan/kargo/{id}', [\App\Http\Controllers\Admin\PesananMasukController::class, 'destroyKargo'])->name('admin.pesanan.kargo.destroy');

    // Validasi Pembayaran
    Route::get('/validasi', [\App\Http\Controllers\Admin\ValidasiPembayaranController::class, 'index'])->name('admin.validasi');
    Route::put('/validasi/acc/{type}/{id}', [\App\Http\Controllers\Admin\ValidasiPembayaranController::class, 'accPembayaran'])->name('admin.validasi.acc');
    Route::put('/validasi/tolak/{type}/{id}', [\App\Http\Controllers\Admin\ValidasiPembayaranController::class, 'tolakPembayaran'])->name('admin.validasi.tolak');

    // Hapus Armada
    Route::delete('/armada/{id}', [\App\Http\Controllers\Admin\ArmadaRuteController::class, 'destroyArmada'])->name('admin.armada.destroy');

    // CRUD Supir
    Route::post('/driver', [\App\Http\Controllers\Admin\ArmadaRuteController::class, 'storeDriver'])->name('admin.driver.store');
    Route::put('/driver/{id}', [\App\Http\Controllers\Admin\ArmadaRuteController::class, 'updateDriver'])->name('admin.driver.update');
    Route::delete('/driver/{id}', [\App\Http\Controllers\Admin\ArmadaRuteController::class, 'destroyDriver'])->name('admin.driver.destroy');

    // Kelola Tarif Kargo Global
    Route::put('/tarif-kargo', [\App\Http\Controllers\Admin\ArmadaRuteController::class, 'updateKargo'])->name('admin.kargo.update');

    // Rute Laporan
    Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('admin.laporan');
    // Rute Export Excel/CSV (BARU)
    Route::get('/laporan/export-rekap', [\App\Http\Controllers\Admin\LaporanController::class, 'exportRekap'])->name('admin.laporan.rekap');
    Route::get('/laporan/export-detail', [\App\Http\Controllers\Admin\LaporanController::class, 'exportDetail'])->name('admin.laporan.detail');
});
<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KaryawanLaporanController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PengaturanController;
use Illuminate\Support\Facades\Route;
use Vinkla\Hashids\Facades\Hashids;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page - Redirect to login for internal system
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes (tidak perlu login)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Protected Routes (perlu login)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // =====================================================
    // MASTER DATA ROUTES (Admin Only)
    // =====================================================
    Route::prefix('master-data')->name('master-data.')->middleware('admin')->group(function () {

        // Cabang Routes
        Route::prefix('cabang')->name('cabang.')->group(function () {
            Route::get('/', [CabangController::class, 'index'])->name('index');
            Route::get('/create', [CabangController::class, 'create'])->name('create');
            Route::post('/', [CabangController::class, 'store'])->name('store');
            Route::get('/{cabang}', [CabangController::class, 'show'])->name('show');
            Route::get('/{cabang}/edit', [CabangController::class, 'edit'])->name('edit');
            Route::put('/{cabang}', [CabangController::class, 'update'])->name('update');
            Route::delete('/{cabang}', [CabangController::class, 'destroy'])->name('destroy');
            Route::post('/{cabang}/toggle-status', [CabangController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('/api/list', [CabangController::class, 'getList'])->name('list');
        });

        // Karyawan Routes
        Route::prefix('karyawan')->name('karyawan.')->group(function () {
            Route::get('/', [KaryawanController::class, 'index'])->name('index');
            Route::get('/create', [KaryawanController::class, 'create'])->name('create');
            Route::post('/', [KaryawanController::class, 'store'])->name('store');
            Route::get('/{karyawan}', [KaryawanController::class, 'show'])->name('show');
            Route::get('/{karyawan}/edit', [KaryawanController::class, 'edit'])->name('edit');
            Route::put('/{karyawan}', [KaryawanController::class, 'update'])->name('update');
            Route::delete('/{karyawan}', [KaryawanController::class, 'destroy'])->name('destroy');
            Route::post('/{karyawan}/toggle-status', [KaryawanController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('/api/by-cabang', [KaryawanController::class, 'getListByCabang'])->name('by-cabang');
        });

        // Laporan Keuangan Routes
        Route::prefix('laporan-keuangan')->name('laporan-keuangan.')->group(function () {
            Route::get('/', [LaporanKeuanganController::class, 'index'])->name('index');
            Route::get('/create', [LaporanKeuanganController::class, 'create'])->name('create');
            Route::post('/', [LaporanKeuanganController::class, 'store'])->name('store');
            Route::get('/export-pdf', [LaporanKeuanganController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/preview-pdf', [LaporanKeuanganController::class, 'previewPdf'])->name('preview-pdf');
            Route::get('/kategori', [LaporanKeuanganController::class, 'getKategori'])->name('kategori');
            Route::get('/{laporanKeuangan}', [LaporanKeuanganController::class, 'show'])->name('show');
            Route::get('/{laporanKeuangan}/edit', [LaporanKeuanganController::class, 'edit'])->name('edit');
            Route::put('/{laporanKeuangan}', [LaporanKeuanganController::class, 'update'])->name('update');
            Route::delete('/{laporanKeuangan}', [LaporanKeuanganController::class, 'destroy'])->name('destroy');
            Route::post('/{laporanKeuangan}/approve', [LaporanKeuanganController::class, 'approve'])->name('approve');
            Route::post('/{laporanKeuangan}/reject', [LaporanKeuanganController::class, 'reject'])->name('reject');
            Route::post('/{laporanKeuangan}/submit-approval', [LaporanKeuanganController::class, 'submitForApproval'])->name('submit-approval');
        });
    });

    // =====================================================
    // ADMIN ONLY ROUTES - Old Routes (untuk kompatibilitas)
    // =====================================================
    Route::middleware('admin')->group(function () {
        // Pemasukan
        Route::get('/pemasukan', function () {
            return redirect()->route('master-data.laporan-keuangan.index', ['jenis' => Hashids::encode(1)]);
        })->name('pemasukan.index');

        Route::get('/pemasukan/create', function () {
            return redirect()->route('master-data.laporan-keuangan.create', ['jenis' => Hashids::encode(1)]);
        })->name('pemasukan.create');

        // Pengeluaran
        Route::get('/pengeluaran', function () {
            return redirect()->route('master-data.laporan-keuangan.index', ['jenis' => Hashids::encode(2)]);
        })->name('pengeluaran.index');

        Route::get('/pengeluaran/create', function () {
            return redirect()->route('master-data.laporan-keuangan.create', ['jenis' => Hashids::encode(2)]);
        })->name('pengeluaran.create');

        // Laporan
        Route::get('/laporan', function () {
            return redirect()->route('master-data.laporan-keuangan.index');
        })->name('laporan.index');

        Route::get('/laporan/harian', [App\Http\Controllers\LaporanController::class, 'harian'])->name('laporan.harian');
        Route::get('/laporan/mingguan', [App\Http\Controllers\LaporanController::class, 'mingguan'])->name('laporan.mingguan');
        Route::get('/laporan/bulanan', [App\Http\Controllers\LaporanController::class, 'bulanan'])->name('laporan.bulanan');

        // =====================================================
        // GAJI ROUTES (Admin Only)
        // =====================================================
        Route::prefix('gaji')->name('gaji.')->group(function () {
            Route::get('/', [GajiController::class, 'index'])->name('index');
            Route::post('/generate', [GajiController::class, 'generate'])->name('generate');
            Route::get('/{id}', [GajiController::class, 'show'])->name('show');
            Route::post('/{id}/mark-paid', [GajiController::class, 'markAsPaid'])->name('mark-paid');
            Route::post('/batch-mark-paid', [GajiController::class, 'batchMarkAsPaid'])->name('batch-mark-paid');
            Route::get('/{id}/slip-pdf', [GajiController::class, 'exportSlipPdf'])->name('slip-pdf');
            Route::get('/export/rekap-pdf', [GajiController::class, 'exportRekapPdf'])->name('rekap-pdf');
        });
    });

    // =====================================================
    // KARYAWAN ROUTES (khusus non-admin)
    // =====================================================
    Route::prefix('karyawan')->name('karyawan.')->middleware('karyawan')->group(function () {
        // Input Pemasukan
        Route::get('/pemasukan', [KaryawanLaporanController::class, 'indexPemasukan'])->name('pemasukan.index');
        Route::get('/pemasukan/create', [KaryawanLaporanController::class, 'createPemasukan'])->name('pemasukan.create');
        Route::post('/pemasukan', [KaryawanLaporanController::class, 'storePemasukan'])->name('pemasukan.store');

        // Laporan Karyawan
        Route::get('/laporan/harian', [KaryawanLaporanController::class, 'laporanHarian'])->name('laporan.harian');
        Route::get('/laporan/mingguan', [KaryawanLaporanController::class, 'laporanMingguan'])->name('laporan.mingguan');
        Route::get('/laporan/bulanan', [KaryawanLaporanController::class, 'laporanBulanan'])->name('laporan.bulanan');
        Route::get('/laporan/riwayat', [KaryawanLaporanController::class, 'riwayat'])->name('laporan.riwayat');

        // Edit, Submit, Delete Draft
        Route::get('/laporan/{laporan}/edit', [KaryawanLaporanController::class, 'editLaporan'])->name('laporan.edit');
        Route::put('/laporan/{laporan}', [KaryawanLaporanController::class, 'updateLaporan'])->name('laporan.update');
        Route::post('/laporan/{laporan}/submit', [KaryawanLaporanController::class, 'submitForApproval'])->name('laporan.submit');
        Route::delete('/laporan/{laporan}', [KaryawanLaporanController::class, 'deleteLaporan'])->name('laporan.delete');

        // Gaji Karyawan (view own gaji)
        Route::get('/gaji', [GajiController::class, 'myGaji'])->name('gaji.index');
        Route::get('/gaji/{id}', [GajiController::class, 'myGajiDetail'])->name('gaji.show');
        Route::get('/gaji/{id}/slip-pdf', [GajiController::class, 'mySlipPdf'])->name('gaji.slip-pdf');

        // Informasi untuk Karyawan
        Route::get('/informasi', [KaryawanLaporanController::class, 'indexInformasi'])->name('informasi.index');
        Route::get('/informasi/{id}', [KaryawanLaporanController::class, 'showInformasi'])->name('informasi.show');
    });

    // =====================================================
    // NOTIFICATION ROUTES
    // =====================================================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/all', [NotificationController::class, 'showAll'])->name('all');
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
    });

    // Produk
    Route::get('/produk', function () {
        return view('pages.produk.index');
    })->name('produk.index');

    // Kategori
    Route::get('/kategori', function () {
        return view('pages.kategori.index');
    })->name('kategori.index');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'index'])->name('index');
        Route::post('/avatar', [App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('avatar.update');
        Route::delete('/avatar', [App\Http\Controllers\ProfileController::class, 'removeAvatar'])->name('avatar.remove');
        Route::put('/update', [App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('update');
        Route::put('/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');
        Route::post('/signature', [App\Http\Controllers\ProfileController::class, 'saveSignature'])->name('signature.save');
        Route::delete('/signature', [App\Http\Controllers\ProfileController::class, 'removeSignature'])->name('signature.remove');
    });

    // =====================================================
    // SETTINGS ROUTES (Admin Only)
    // =====================================================
    Route::prefix('settings')->name('settings.')->middleware('admin')->group(function () {
        Route::get('/', [PengaturanController::class, 'index'])->name('index');
        Route::put('/persen-gaji', [PengaturanController::class, 'updatePersenGaji'])->name('persen-gaji.update');

        // Informasi Routes
        Route::post('/informasi', [PengaturanController::class, 'storeInformasi'])->name('informasi.store');
        Route::get('/informasi/{id}', [PengaturanController::class, 'showInformasi'])->name('informasi.show');
        Route::put('/informasi/{id}', [PengaturanController::class, 'updateInformasi'])->name('informasi.update');
        Route::delete('/informasi/{id}', [PengaturanController::class, 'destroyInformasi'])->name('informasi.destroy');
        Route::delete('/informasi/{id}/lampiran', [PengaturanController::class, 'removeLampiran'])->name('informasi.lampiran.remove');
    });

    // =====================================================
    // INFORMASI PUBLIC ROUTES (untuk karyawan melihat detail)
    // =====================================================
    Route::get('/informasi/{id}', [PengaturanController::class, 'showInformasi'])->name('informasi.show');
});

<?php

use App\Http\Controllers\AlumniController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailPembayaranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\NamaPembayaranController;
use App\Http\Controllers\JenisPembayaranController;
use App\Http\Controllers\KenaikanKelasController;
use App\Http\Controllers\KelulusanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TunggakanController;
use App\Http\Controllers\BroadcastController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\LaporanTarifController;
use App\Http\Controllers\LaporanPembayaranController;
use App\Http\Controllers\LaporanTotalController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    
    return redirect()->route('login');
});

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'authenticate']);
Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

Route::get('siswa/export', [SiswaController::class, 'export'])->name('siswa.export');
Route::get('siswa/exportPdf', [SiswaController::class, 'exportPdf'])->name('siswa.exportPdf');
Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');

Route::get('/laporan-tarif/export-pdf', [LaporanTarifController::class, 'exportPDF'])->name('laporan-tarif.exportPDF');
Route::get('/laporan-tarif/export-excel', [LaporanTarifController::class, 'exportExcel'])->name('laporan-tarif.export-excel');
Route::get('/laporan-tarif/getData', [LaporanTarifController::class, 'getData'])->name('laporan-tarif.getData');

Route::get('/laporan-pembayaran/export-pdf', [LaporanPembayaranController::class, 'exportPDF'])->name('laporan-pembayaran.export-pdf');
Route::get('/laporan-pembayaran/export-excel', [LaporanPembayaranController::class, 'exportExcel'])
    ->name('laporan-pembayaran.export-excel');

Route::resource('laporan-total', LaporanTotalController::class);


Route::post('/profile/update-image', [ProfileController::class, 'updateImage'])->name('profile.updateImage');


Route::group(['middleware' => 'auth'], function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::get('dashboard', function () { 
    //     return view('pages.dashboard');
    // })->name('dashboard');

    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::delete('profile/delete-account', [ProfileController::class, 'deleteAccount'])->name('profile.delete-account');
    Route::post('/profile/update-image', [ProfileController::class, 'updateImage'])->name('profile.update-image');

    
    Route::group(['middleware' => 'role:1'], function () {
        Route::group(['prefix' => 'data'], function () {
            Route::resource('tahun-ajaran', TahunAjaranController::class);

            Route::resource('petugas', PetugasController::class);

            Route::resource('siswa', SiswaController::class);

            Route::resource('kelas', KelasController::class);

            Route::get('kenaikan-kelas', [KenaikanKelasController::class, 'index'])->name('kenaikan-kelas.index');
            Route::post('kenaikan-kelas', [KenaikanKelasController::class, 'naikKelas'])->name('kenaikan-kelas.naikKelas');
            Route::get('/kenaikan-kelas/check-unpaid', [KenaikanKelasController::class, 'checkUnpaid'])->name('kenaikan-kelas.checkUnpaid');
            Route::get('/kenaikan-kelas/get-siswa', [KenaikanKelasController::class, 'getSiswaByKelasAndTahunAjaran'])->name('kenaikan-kelas.getSiswa');


            Route::resource('kelulusan', KelulusanController::class);
            Route::get('kelulusan', function () {
                return view('pages.data.kelulusan.index');
            })->name('kelulusan');

            // Route::get('alumni', function () {
            //     return view('pages.data.lunas.index');
            // })->name('alumni');
        });

        // Route::get('broadcast', function () {
        //     return view('pages.broadcast.index');
        // })->name('broadcast');

        Route::get('broadcast', [BroadcastController::class, 'index'])->name('broadcast');
        Route::post('broadcast/doAll', [BroadcastController::class, 'doBroadcast'])->name('doBroadcast');
        Route::post('broadcast/doBroadcastTarget', [BroadcastController::class, 'doBroadcastTarget'])->name('doBroadcastTarget');

        Route::get('tunggakan', [TunggakanController::class, 'index'])->name('tunggakan.index');
        // Route::get('tunggakan/export', [TunggakanController::class, 'export'])->name('tunggakan.export');
        // Route::get('tunggakan/exportPdf', [TunggakanController::class, 'export'])->name('tunggakan.exportPdf');
        Route::get('tunggakan/export', [TunggakanController::class, 'export'])->name('tunggakan.export');
        Route::get('tunggakan/exportPdf', [TunggakanController::class, 'exportPdf'])->name('tunggakan.exportPdf');
        

        Route::group(['prefix' => 'keuangan'], function () {

            Route::resource('nama-transaksi', NamaPembayaranController::class);

            Route::resource('jenis-transaksi', JenisPembayaranController::class);

            Route::get('jenis-transaksi/{id}/tarif', [TarifController::class, 'index'])->name('tarif.index');
            Route::get('jenis-transaksi/{id}/tarif/create', [TarifController::class, 'create'])->name('tarif.create');
            Route::post('jenis-transaksi/{id}/tarif', [TarifController::class, 'store'])->name('tarif.store');
            Route::get('jenis-transaksi/{id}/tarif/{id_tarif}', [TarifController::class, 'show'])->name('tarif.show');
            Route::get('jenis-transaksi/{id}/tarif/{id_tarif}/edit', [TarifController::class, 'edit'])->name('tarif.edit');
            Route::put('jenis-transaksi/{id}/tarif/{id_tarif}', [TarifController::class, 'update'])->name('tarif.update');
            Route::delete('jenis-transaksi/{id}/tarif/{id_tarif}', [TarifController::class, 'destroy'])->name('tarif.destroy');
        });

        // tambahan

        Route::get('kelulusan', [KelulusanController::class, 'index'])->name('kelulusan.index');
        Route::post('kelulusan', [KelulusanController::class, 'naikKelas'])->name('kelulusan.naikKelas');
        Route::get('/kelulusan/check-unpaid', [KelulusanController::class, 'checkUnpaid'])->name('kelulusan.checkUnpaid');
        Route::get('/kelulusan/getSiswa', [KelulusanController::class, 'getSiswa'])->name('kelulusan.getSiswa');

        Route::group(['prefix' => 'arsip'], function () {
            Route::get('kelulusan', function () {
                return view('pages.arsip.kelulusan.index');
            })->name('kelulusan');
           
            Route::get('alumni', [AlumniController::class, 'index'])->name('alumni.index');
        });

        Route::group(['prefix' => 'settings'], function () {
            Route::get('aplikasi', function () {
                return view('pages.settings.aplikasi.index');
            })->name('aplikasi');

            Route::get('tahun-ajaran', function () {
                return view('pages.settings.tahun-ajaran.index');
            })->name('tahun-ajaran');

            Route::get('tagihan', function () {
                return view('pages.settings.tagihan.index');
            })->name('tagihan');

            Route::get('jenis-transaksi', function () {
                return view('pages.settings.jenis-transaksi.index');
            })->name('jenis-transaksi');

            Route::get('kelulusan', function () {
                return view('pages.settings.kelulusan.index');
            })->name('kelulusan');

            Route::get('kenaikan-kelas', function () {
                return view('pages.settings.kenaikan-kelas.index');
            })->name('kenaikan-kelas');
        });
    });
    // Route::group(['middleware' => 'role:1,2'], function () {
    //     Route::get('laporan', function () {
    //         return view('pages.laporan.index');
    //     })->name('laporan');
    // });

    Route::group(['middleware' => 'role:1,2'], function () {
        Route::get('laporan', function () {
            return view('pages.laporan.index');
        })->name('laporan');
    
        // Route::get('laporan/laporan-pembayaran', function () {
        //     return view('pages.laporan.laporan-pembayaran.index');
        // })->name('laporan-pembayaran');
    
        Route::resource('laporan-pembayaran', LaporanPembayaranController::class);
        Route::resource('laporan-total', LaporanTotalController::class);
        Route::get('laporan/laporan-total', function () {
                return view('pages.laporan.laporan-total.index');
            })->name('laporan-total');

            // Route::get('/laporan-total', [LaporanTotalController::class, 'laporanTotal']);

        // Route::get('laporan/laporan-tarif', 
        // [LaporanTarifController::class, 'index'])->name('laporan-tarif');
        Route::resource('laporan-tarif', LaporanTarifController::class);
        // Route::get('laporan/laporan-tarif', function () {
        //     return view('pages.laporan.laporan-tarif.index');
        // })->name('laporan-tarif');
    });

    Route::group(['middleware' => 'role:1,3'], function () {
        Route::resource('transaksi', PembayaranController::class);
        Route::post('transaksi/spp/{id}', [DetailPembayaranController::class, 'createPaymentMonthly'])->name('transaksi.createPaymentMonthly');
        Route::post('transaksi/lain/{id}', [DetailPembayaranController::class, 'createPayment'])->name('transaksi.createPayment');
        Route::post('transaksi/spp/{id}/ulang', [DetailPembayaranController::class, 'repayMonthly'])->name('transaksi.repayMonthly');
        Route::post('transaksi/lain/{id}/ulang', [DetailPembayaranController::class, 'repay'])->name('transaksi.repay');
        Route::post('/notification/handler', [DetailPembayaranController::class, 'notificationHandler'])->name('notification.handler');

        Route::get('transaksi/spp/{id}', [PembayaranController::class, 'show'])->name('transaksi.show');
        Route::get('transaksi/lain/{id}', [PembayaranController::class, 'showLain'])->name('transaksi.showLain');

        Route::get('/transaksi/{id}/invoice', [DetailPembayaranController::class, 'generateInvoice'])->name('transaksi.invoice');

        Route::get('transaksi/spp/{id}/export', [DetailPembayaranController::class, 'exportMonthly'])->name('transaksi.exportMonthly');
        Route::get('transaksi/lain/{id}/export', [DetailPembayaranController::class, 'export'])->name('transaksi.export');

        Route::get('transaksi/lain/{id}/export', [DetailPembayaranController::class, 'export'])->name('transaksi.export');
        Route::get('transaksi/lain/{id}/exportPdf', [DetailPembayaranController::class, 'exportPdf'])->name('transaksi.exportPdf');
        
        Route::get('transaksi/spp/{id}/exportMonthly', [DetailPembayaranController::class, 'exportMonthly'])->name('transaksi.exportMonthly');
        Route::get('transaksi/spp/{id}/exportBulanan', [DetailPembayaranController::class, 'exportBulanan'])->name('transaksi.exportBulanan');

        
    });

});

<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Pembayaran;
use App\Scopes\LulusScope;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil ID role pengguna yang sedang masuk
        $id_role = auth()->user()->id_role;
    
        // Inisialisasi variabel untuk menyimpan data total
        $total_pembayaran = 0;
        $total_siswa = 0;
        $total_kelas = 0;
        $total_bulanan = 0;
        $total_lain_lain = 0;
    
        // Inisialisasi variabel untuk menyimpan data pembayaran harian
        $daily_payment_labels = [];
        $daily_payment_data = [];
    
        // Dapatkan bulan dan tahun saat ini
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
    
        // Jika role adalah petugas tata usaha atau kepala sekolah
        if ($id_role == 1 || $id_role == 2) {
            // Menghitung total pembayaran untuk semua pembayaran yang statusnya Lunas
            $total_pembayaran = DetailPembayaran::sum('jumlah_transaksi');
    
            // Menghitung total pembayaran bulanan
            $total_bulanan = DetailPembayaran::whereHas('transaksi.jenisPembayaran', function ($query) {
                $query->where('tipe_bayar', 'Bulanan');
            })->sum('jumlah_transaksi');
    
            // Menghitung total pembayaran bebas
            $total_lain_lain = DetailPembayaran::whereHas('transaksi.jenisPembayaran', function ($query) {
                $query->where('tipe_bayar', 'Bebas');
            })->sum('jumlah_transaksi');
    
            // Menghitung total jumlah siswa dan kelas
            // $total_siswa = Siswa::where('status', 'Aktif')->count();
            $total_siswa = Siswa::count();

    
            // Menghitung jumlah siswa yang membayar per hari dalam bulan saat ini
            $monthly_payments = DetailPembayaran::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->selectRaw('DAY(created_at) as day, COUNT(DISTINCT id_siswa) as count')
                ->groupBy('day')
                ->get();
    
            // Membuat array tanggal 1 sampai dengan jumlah hari dalam bulan saat ini
            $days_in_month = range(1, Carbon::now()->daysInMonth);
            $daily_payment_labels = array_map(function($day) {
                return $day;
            }, $days_in_month);
    
            // Mengisi data pembayaran harian untuk setiap hari dalam bulan saat ini
            $daily_payment_data = array_fill(0, count($days_in_month), 0);
            foreach ($monthly_payments as $payment) {
                $daily_payment_data[$payment->day - 1] = $payment->count;
            }
        }
        // Jika role adalah siswa
        // elseif ($id_role == 3) {
        //     $id_user = auth()->user()->id_user;
        //     $siswa = Siswa::where('id_user', $id_user)->first();
        //     $id_siswa = $siswa ? $siswa->id_siswa : null;
        elseif ($id_role == 3) {
            $id_user = auth()->user()->id_user;
            $siswa = Siswa::withoutGlobalScope(LulusScope::class)->where('id_user', $id_user)->whereIn('status', [0, 1])->first();
            $id_siswa = $siswa ? $siswa->id_siswa : null;
    
            if ($id_siswa) {
                // Menghitung total pembayaran untuk semua jenis pembayaran dari siswa tertentu
                $total_pembayaran = DetailPembayaran::whereHas('transaksi', function ($query) use ($id_siswa) {
                    $query->where('id_siswa', $id_siswa);
                })->sum('jumlah_transaksi');
    
                // Menghitung total pembayaran bulanan
                $total_bulanan = DetailPembayaran::whereHas('transaksi.jenisPembayaran', function ($query) {
                    $query->where('tipe_bayar', 'Bulanan');
                })->whereHas('transaksi', function ($query) use ($id_siswa) {
                    $query->where('id_siswa', $id_siswa);
                })->sum('jumlah_transaksi');
    
                // Menghitung total pembayaran bebas
                $total_lain_lain = DetailPembayaran::whereHas('transaksi.jenisPembayaran', function ($query) {
                    $query->where('tipe_bayar', 'Bebas');
                })->whereHas('transaksi', function ($query) use ($id_siswa) {
                    $query->where('id_siswa', $id_siswa);
                })->sum('jumlah_transaksi');
            }
        }

        // Mengembalikan view dashboard dengan data yang sesuai
        return view('pages.dashboard', compact(
            'total_pembayaran', 
            'total_siswa', 
            'total_kelas', 
            'total_bulanan', 
            'total_lain_lain',
            'daily_payment_labels',
            'daily_payment_data'
        ));
    }
}

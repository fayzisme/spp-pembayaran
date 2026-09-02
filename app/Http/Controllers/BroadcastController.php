<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\JenisPembayaran;
use App\Models\TahunAjaran;
use App\Models\Pembayaran;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BroadcastController extends Controller
{   
    // public function index()
    // {
    //     $dataSiswaAll = Siswa::all();
    //     $dataJenisPembayaran = JenisPembayaran::all()->unique('nama_pembayaran'); // Menghindari duplikasi
    //     return view('pages.broadcast.index')->with([
    //         'datasiswaAll' => $dataSiswaAll,
    //         'datajnsPembayaran' => $dataJenisPembayaran,
    //     ]);
    // }
    public function index()
    {
        $dataSiswaAll = Siswa::all();
        $dataJenisPembayaran = JenisPembayaran::all();
        $dataTahunAjaran = TahunAjaran::all(); // Ambil data tahun ajaran

        return view('pages.broadcast.index')->with([
            'datasiswaAll' => $dataSiswaAll,
            'datajnsPembayaran' => $dataJenisPembayaran,
            'dataTahunAjaran' => $dataTahunAjaran,
        ]);
    }

    // public function doBroadcast(Request $request)
    // {
    //     $request->validate([
    //         'jnspembayaran' => 'required',
    //     ],[
    //         'jnspembayaran.required' => 'Pilih salah satu!',
    //     ]);
        
    //     $dataSiswa = Siswa::all();

    //     $implodeResult = $dataSiswa->map(function ($siswa) use ($request) {
    //         // kelas
    //         $dataKelas = DB::table('kelas')->where('id_kelas', $siswa->id_kelas)->first();
    //         $kelas = $dataKelas->tingkat . ' (' . $dataKelas->nama_kelas . ')';

    //         $dataTransaksi = DB::table('transaksi')->where('id_siswa', $siswa->id_siswa)->where('id_jenis_pembayaran', $request->jnspembayaran)->first();

    //         // jenis pembayaran
    //         $dataJenisPembayaran = JenisPembayaran::find($request->jnspembayaran);
    //         $perihal = $dataJenisPembayaran->nama_pembayaran;
            
    //         //kondisional jika $dataTransaksi kosong
    //         if ($dataTransaksi == null) {
    //             $tarif = '0';
    //             $tahunajaran = 'Unknown';
    //         } else {
    //             // tahun ajaran
    //             $dataTahunAjar = DB::table('thn_ajaran')->where('id_thn_ajaran', $dataTransaksi->id_thn_ajaran)->first();
    //             $tahunajaran = 'T.A ' . $dataTahunAjar->thn_ajaran . ' ' . $dataTahunAjar->semester;
    //             // tarif
    //              if ($dataJenisPembayaran->tipe_bayar == 'Bulanan') {
    //                 $tarif = $dataTransaksi->total_bayar . ' (untuk 1 Semester)';
    //             } else {
    //                 // $tarif = $dataTransaksi->total_bayar . ' (per bulan)';
    //                 $tarif = $dataTransaksi->total_bayar;
    //             }
    //         }

    //         return "{$siswa->no_hp}|{$siswa->nama}|{$tahunajaran}|{$tarif}|{$kelas}|{$perihal}";
    //     })->implode(',');

    //     $text_message = '*Pemberitahuan {var4} - {var1}*' . "\n\n" . 'Diberitahukan kepada orang tua/wali dari {name} dengan detail sebagai berikut : ' ."\n\n" . '*Nama : {name}*' . "\n" . '*Kelas : {var3}*' . "\n" . '*Jumlah Tagihan : RP. {var2}*' . "\n" . '*Tagihan : {var4} - {var1}*' . "\n" . '*TA : {var1}*' . "\n\n" . 'Diharapkan untuk segera melunasi tagihan tersebut. Segera datang ke Bagian Administrasi untuk melakukan pembayaran.' . "\n\n" . 'Demikian Terima Kasih' . "\n" . 'Salam,' . "\n" . 'Bagian Tata Usaha' . "\n\n" . '*_Abaikan pesan berikut jika telah melakukan pembayaran_*';

    //     $additionalData = [
    //         'target' => $implodeResult,
    //         'text_message' => $text_message,
    //     ];

    //     // return view('pages.broadcast.testerbroadcast')->with($additionalData);

    //     WhatsappController::notificationAll($additionalData);

    //     return redirect('broadcast');
    // }
    // public function doBroadcast(Request $request)
    // {
    //     $request->validate([
    //         'jnspembayaran' => 'required',
    //     ],[
    //         'jnspembayaran.required' => 'Pilih salah satu!',
    //     ]);
        
    //     $dataSiswa = Siswa::all();

    //     $implodeResult = $dataSiswa->map(function ($siswa) use ($request) {
    //         // kelas
    //         $dataKelas = DB::table('kelas')->where('id_kelas', $siswa->id_kelas)->first();
    //         $kelas = $dataKelas->tingkat . ' (' . $dataKelas->nama_kelas . ')';

    //         $dataTransaksi = DB::table('transaksi')->where('id_siswa', $siswa->id_siswa)->where('id_jenis_pembayaran', $request->jnspembayaran)->first();

    //         // jenis pembayaran
    //         $dataJenisPembayaran = JenisPembayaran::find($request->jnspembayaran);
    //         $perihal = $dataJenisPembayaran->nama_pembayaran;
            
    //         //kondisional jika $dataTransaksi kosong
    //         if ($dataTransaksi == null) {
    //             $tarif = '0';
    //             $tahunajaran = 'Belum Ada';
    //         } else {
    //             // tahun ajaran
    //             $dataTahunAjar = DB::table('thn_ajaran')->where('id_thn_ajaran', $dataTransaksi->id_thn_ajaran)->first();
    //             $tahunajaran = 'T.A ' . $dataTahunAjar->thn_ajaran . ' ' . $dataTahunAjar->semester;

    //             // menghitung sisa tagihan
    //             $totalBayar = $dataTransaksi->total_bayar;
    //             $totalTransaksiDetail = DB::table('detail_transaksi')->where('id_transaksi', $dataTransaksi->id_transaksi)->sum('jumlah_transaksi');
    //             $sisaTagihan = $totalBayar - $totalTransaksiDetail;

    //             $tarif = number_format($sisaTagihan, 0, ',', '.'); // Format nominal

    //             if ($dataJenisPembayaran->tipe_bayar == 'Bulanan') {
    //                 $tarif .= ' (untuk 1 Semester)';
    //             }
    //         }

    //         return "{$siswa->no_hp}|{$siswa->nama}|{$tahunajaran}|{$tarif}|{$kelas}|{$perihal}";
    //     })->implode(',');

    //     $text_message = '*Pemberitahuan {var4} - {var1}*' . "\n\n" . 'Diberitahukan kepada orang tua/wali dari {name} dengan detail sebagai berikut : ' ."\n\n" . '*Nama : {name}*' . "\n" . '*Kelas : {var3}*' . "\n" . '*Jumlah Tagihan : RP. {var2}*' . "\n" . '*Tagihan : {var4} - {var1}*' . "\n\n" . 'Diharapkan untuk segera melunasi tagihan tersebut. Segera datang ke Bagian Administrasi untuk melakukan pembayaran.' . "\n\n" . 'Demikian Terima Kasih' . "\n" . 'Salam,' . "\n" . 'Bagian Tata Usaha' . "\n\n" . '*_Abaikan pesan berikut jika telah melakukan pembayaran_*';

    //     $additionalData = [
    //         'target' => $implodeResult,
    //         'text_message' => $text_message,
    //     ];

    //     WhatsappController::notificationAll($additionalData);

    //     return redirect('broadcast');
    // }
    public function doBroadcast(Request $request)
{
    // Validasi input
    $request->validate([
        'jnspembayaran' => 'required',
    ],[
        'jnspembayaran.required' => 'Pilih salah satu!',
    ]);
    
    // Mendapatkan data semua siswa
    $dataSiswa = Siswa::all();

    // Mengolah data setiap siswa
    $implodeResult = $dataSiswa->map(function ($siswa) use ($request) {
        // Mendapatkan data kelas siswa
        $dataKelas = DB::table('kelas')->where('id_kelas', $siswa->id_kelas)->first();
        $kelas = $dataKelas->tingkat . ' (' . $dataKelas->nama_kelas . ')';

        // Mendapatkan data transaksi berdasarkan siswa dan jenis pembayaran
        $dataTransaksi = DB::table('transaksi')
            ->where('id_siswa', $siswa->id_siswa)
            ->where('id_jenis_pembayaran', $request->jnspembayaran)
            ->first();

        // Mendapatkan data jenis pembayaran
        $dataJenisPembayaran = JenisPembayaran::find($request->jnspembayaran);
        $perihal = $dataJenisPembayaran->nama_pembayaran;
        
        // Jika data transaksi kosong
        if ($dataTransaksi == null) {
            $tarif = '0';
            $tahunajaran = 'Belum Ada';
        } else {
            // Mendapatkan data tahun ajaran
            $dataTahunAjar = DB::table('thn_ajaran')->where('id_thn_ajaran', $dataTransaksi->id_thn_ajaran)->first();
            $tahunajaran = 'T.A ' . $dataTahunAjar->thn_ajaran . ' ' . $dataTahunAjar->semester;

            // Menghitung sisa tagihan
            $totalBayar = $dataTransaksi->total_bayar;
            $totalTransaksiDetail = DB::table('detail_transaksi')
                ->where('id_transaksi', $dataTransaksi->id_transaksi)
                ->sum('jumlah_transaksi');
            $sisaTagihan = $totalBayar - $totalTransaksiDetail;

            $tarif = number_format($sisaTagihan, 0, ',', '.'); // Format nominal

            // Menambahkan keterangan jika pembayaran bersifat bulanan
            if ($dataJenisPembayaran->tipe_bayar == 'Bulanan') {
                $tarif .= ' (untuk 1 Semester)';
            }
        }

        // Menggabungkan data siswa
        return "{$siswa->no_hp}|{$siswa->nama}|{$tahunajaran}|{$tarif}|{$kelas}|{$perihal}";
    })->implode(',');

    // Membuat pesan notifikasi
    $text_message = '*Pemberitahuan {var4} - {var1}*' . "\n\n" . 
                    'Diberitahukan kepada orang tua/wali dari {name} dengan detail sebagai berikut :' ."\n\n" . 
                    '*Nama : {name}*' . "\n" . 
                    '*Kelas : {var3}*' . "\n" . 
                    '*Jumlah Tagihan : RP. {var2}*' . "\n" . 
                    '*Tagihan : {var4} - {var1}*' . "\n\n" . 
                    'Diharapkan untuk segera melunasi tagihan tersebut. Segera datang ke Bagian Administrasi untuk melakukan pembayaran.' . "\n\n" . 
                    'Demikian Terima Kasih' . "\n" . 
                    'Salam,' . "\n" . 
                    'Bagian Tata Usaha' . "\n\n" . 
                    '*_Abaikan pesan berikut jika telah melakukan pembayaran_*';

    // Data tambahan untuk notifikasi
    $additionalData = [
        'target' => $implodeResult,
        'text_message' => $text_message,
    ];

    // Mengirim notifikasi
    WhatsappController::notificationAll($additionalData);

    // Redirect ke halaman broadcast
    return redirect('broadcast');
}

    // public function doBroadcastTarget(Request $request)
    // {
    //     $request->validate([
    //         'target' => 'required',
    //         'jnspembayaran' => 'required',
    //     ],[
    //         'target.required' => 'Pilih salah satu!',
    //         'jnspembayaran.required' => 'Pilih salah satu!',
    //     ]);
        
    //     $dataSiswa = Siswa::find($request->target);

    //     // kelas
    //     $dataKelas = DB::table('kelas')->where('id_kelas', $dataSiswa->id_kelas)->first();
    //     $kelas = $dataKelas->tingkat . ' (' . $dataKelas->nama_kelas . ')';

    //     // transaksi
    //     $dataTransaksi = DB::table('transaksi')->where('id_siswa', $dataSiswa->id_siswa)->where('id_jenis_pembayaran', $request->jnspembayaran)->first();

    //     // jenis pembayaran
    //     $dataJenisPembayaran = JenisPembayaran::find($request->jnspembayaran);
        
    //     //kondisional jika $dataTransaksi kosong
    //     if ($dataTransaksi == null) {
    //         $tarif = '0';
    //         $tahunajaran = 'Unknown';
    //     } else {
    //         // tahun ajaran
    //         $dataTahunAjar = DB::table('thn_ajaran')->where('id_thn_ajaran', $dataTransaksi->id_thn_ajaran)->first();
    //         $tahunajaran = 'T.A ' . $dataTahunAjar->thn_ajaran . ' ' . $dataTahunAjar->semester;
    //         // tarif
    //         if ($dataJenisPembayaran->tipe_bayar == 'Bulanan') {
    //             $tarif = $dataTransaksi->total_bayar . ' (untuk 6 bulan / 1 Semester)';
    //         } else {
    //             $tarif = $dataTransaksi->total_bayar . ' (per bulan)';
    //         }
    //     }

    //     $text_message = '*Pemberitahuan ' . $dataJenisPembayaran->nama_pembayaran . ' - ' . $tahunajaran .  '*' . "\n\n" . 'Diberitahukan kepada orang tua/wali dari ' . $dataSiswa->nama . ' dengan detail sebagai berikut : ' ."\n\n" . '*Nama : ' . $dataSiswa->nama . '*' . "\n" . '*Kelas : ' . $kelas . '*' . "\n" . '*Jumlah Tagihan : Rp.' . $tarif . '*' . "\n" . '*Tagihan : ' . $dataJenisPembayaran->nama_pembayaran . ' - ' . $tahunajaran . '*' . "\n" . '*TA : ' . $tahunajaran . '*' . "\n\n" . 'Diharapkan untuk segera melunasi tagihan tersebut. Segera datang ke Bagian Administrasi untuk melakukan pembayaran.' . "\n\n" . 'Demikian Terima Kasih' . "\n" . 'Salam,' . "\n" . 'Bagian Tata Usaha' . "\n\n" . '*_Abaikan pesan berikut jika telah melakukan pembayaran_*';

    //     $additionalData = [
    //         'target' => $dataSiswa->no_hp,
    //         'text_message' => $text_message,
    //     ];

    //     WhatsappController::notification($additionalData);

    //     return redirect('broadcast');
    // }
    public function doBroadcastTarget(Request $request)
    {
        $request->validate([
            'target' => 'required',
            'jnspembayaran' => 'required',
        ],[
            'target.required' => 'Pilih salah satu!',
            'jnspembayaran.required' => 'Pilih salah satu!',
        ]);
        
        $dataSiswa = Siswa::find($request->target);

        // kelas
        $dataKelas = DB::table('kelas')->where('id_kelas', $dataSiswa->id_kelas)->first();
        $kelas = $dataKelas->tingkat . ' (' . $dataKelas->nama_kelas . ')';

        // transaksi
        $dataTransaksi = DB::table('transaksi')->where('id_siswa', $dataSiswa->id_siswa)->where('id_jenis_pembayaran', $request->jnspembayaran)->first();

        // jenis pembayaran
        $dataJenisPembayaran = JenisPembayaran::find($request->jnspembayaran);
        
        //kondisional jika $dataTransaksi kosong
        if ($dataTransaksi == null) {
            $tarif = '0';
            $tahunajaran = 'Belum Ada';
        } else {
            // tahun ajaran
            $dataTahunAjar = DB::table('thn_ajaran')->where('id_thn_ajaran', $dataTransaksi->id_thn_ajaran)->first();
            $tahunajaran = 'T.A ' . $dataTahunAjar->thn_ajaran . ' ' . $dataTahunAjar->semester;

            // menghitung sisa tagihan
            $totalBayar = $dataTransaksi->total_bayar;
            $totalTransaksiDetail = DB::table('detail_transaksi')->where('id_transaksi', $dataTransaksi->id_transaksi)->sum('jumlah_transaksi');
            $sisaTagihan = $totalBayar - $totalTransaksiDetail;

            $tarif = number_format($sisaTagihan, 0, ',', '.'); // Format nominal

            if ($dataJenisPembayaran->tipe_bayar == 'Bulanan') {
                $tarif .= ' (untuk 1 Semester)';
            }
        }

        $text_message = '*Pemberitahuan ' . $dataJenisPembayaran->nama_pembayaran . ' - ' . $tahunajaran .  '*' . "\n\n" . 'Diberitahukan kepada orang tua/wali dari ' . $dataSiswa->nama . ' dengan detail sebagai berikut : ' ."\n\n" . '*Nama : ' . $dataSiswa->nama . '*' . "\n" . '*Kelas : ' . $kelas . '*' . "\n" . '*Jumlah Tagihan : Rp.' . $tarif . '*' . "\n" . '*Tagihan : ' . $dataJenisPembayaran->nama_pembayaran . ' - ' . $tahunajaran . '*' . "\n\n" . 'Diharapkan untuk segera melunasi tagihan tersebut. Segera datang ke Bagian Tata Usaha untuk melakukan pembayaran atau dapat melakukan pembayaran secara online.' . "\n\n" . 'Demikian Terima Kasih' . "\n" . 'Salam,' . "\n" . 'Bagian Tata Usaha' . "\n\n" . '*_Abaikan pesan berikut jika telah melakukan pembayaran_*';

        $additionalData = [
            'target' => $dataSiswa->no_hp,
            'text_message' => $text_message,
        ];

        WhatsappController::notification($additionalData);

        return redirect('broadcast');
    }
}

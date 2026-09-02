<?php

namespace App\Http\Controllers;

use App\Exports\TunggakanExport;
use App\Models\JenisPembayaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class TunggakanController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = Siswa::query();
    
    //     if ($request->filled('id_kelas')) {
    //         $query->where('id_kelas', $request->id_kelas);
    //     }
    
    //     if ($request->filled('id_thn_ajaran')) {
    //         $query->whereHas('transaksi.tahunAjaran', function($q) use ($request) {
    //             $q->where('id_thn_ajaran', $request->id_thn_ajaran);
    //         });
    //     }
    
    //     $siswa = $query->with(['transaksi' => function($q) {
    //         $q->with('tarif', 'detailPembayaran', 'tahunAjaran', 'jenisPembayaran');
    //     }, 'kelas'])->latest()->paginate(10);
    
    //     $kelas = Kelas::all();
    //     $tahunAjaran = TahunAjaran::all();
    
    //     return view('pages.tunggakan.index', compact('siswa', 'kelas', 'tahunAjaran', 'request'));
    // }
//     public function index(Request $request)
// {
//     $query = Siswa::query();

//     if ($request->filled('id_kelas')) {
//         $query->where('id_kelas', $request->id_kelas);
//     }

//     if ($request->filled('id_thn_ajaran')) {
//         $query->whereHas('transaksi.tahunAjaran', function($q) use ($request) {
//             $q->where('id_thn_ajaran', $request->id_thn_ajaran);
//         });
//     }

//     $siswa = $query->with(['transaksi' => function($q) {
//         $q->with('tarif', 'detailPembayaran', 'tahunAjaran', 'jenisPembayaran', 'kelas');
//     }, 'kelas'])->latest()->paginate(10);

//     $kelas = Kelas::all();
//     $tahunAjaran = TahunAjaran::all();

//     foreach ($siswa as $student) {
//         foreach ($student->transaksi as $transaksi) {
//             $transaksi->total_bayar = $transaksi->tarif->tarif ?? 0; // Total bayar from the tarif field in the transaksi table
//             $transaksi->total_dibayar = $transaksi->detailPembayaran->sum('jumlah_transaksi'); // Sum of jumlah_transaksi in detailPembayaran
//             $transaksi->sisa_tanggungan = $transaksi->total_bayar - $transaksi->total_dibayar; // sisa tanggungan calculation
//         }
//     }

//     return view('pages.tunggakan.index', compact('siswa', 'kelas', 'tahunAjaran', 'request'));
// }
public function index(Request $request)
{
    $query = Siswa::query();

    if ($request->filled('id_kelas')) {
        $query->where('id_kelas', $request->id_kelas);
    }

    if ($request->filled('id_thn_ajaran')) {
        $query->whereHas('transaksi.tahunAjaran', function($q) use ($request) {
            $q->where('id_thn_ajaran', $request->id_thn_ajaran);
        });
    }

    $siswa = $query->with(['transaksi' => function($q) {
        $q->with('tarif', 'detailPembayaran', 'tahunAjaran', 'jenisPembayaran', 'kelas');
    }, 'kelas'])->latest()->paginate(100);

    $kelas = Kelas::all();
    $tahunAjaran = TahunAjaran::all();

    return view('pages.tunggakan.index', compact('siswa', 'kelas', 'tahunAjaran', 'request'));
}



    
    
    

//     public function exportPdf(Request $request)
// {
//     $query = Siswa::with(['transaksi', 'kelas']);

//     // Filter berdasarkan tahun ajaran jika dipilih
//     if ($request->has('id_thn_ajaran') && !empty($request->id_thn_ajaran)) {
//         $query->whereHas('transaksi', function($q) use ($request) {
//             $q->where('id_thn_ajaran', $request->id_thn_ajaran);
//         });
//     }

//     // Filter berdasarkan kelas jika dipilih
//     if ($request->has('id_kelas') && !empty($request->id_kelas)) {
//         $query->where('id_kelas', $request->id_kelas);
//     }

//     // Ambil data siswa dengan filter yang diterapkan
//     $siswa = $query->get();

//     // Cek apakah data kosong
//     if ($siswa->isEmpty()) {
//         // Anda bisa memberikan pesan error atau penanganan khusus jika tidak ada data
//         return response()->json(['message' => 'Data tidak ditemukan'], 404);
//     }

//     // Load view dan generate PDF
//     $pdf = Pdf::loadView('eksporpdf.tunggakan', compact('siswa'))->setPaper('a4', 'landscape');
//     return $pdf->stream('Data Tagihan Siswa.pdf');
// }
public function exportPdf(Request $request)
{
    $query = Siswa::with(['kelas', 'transaksi.jenisPembayaran', 'transaksi.tahunAjaran', 'transaksi.detailPembayaran', 'transaksi.tarif']);

    if ($request->filled('id_thn_ajaran')) {
        $query->whereHas('transaksi', function ($q) use ($request) {
            $q->where('id_thn_ajaran', $request->id_thn_ajaran);
        });
    }

    if ($request->filled('id_kelas')) {
        $query->where('id_kelas', $request->id_kelas);
    }

    $siswa = $query->get();

    $pdf = Pdf::loadView('eksporpdf.tunggakan', compact('siswa'))->setPaper('a4', 'landscape');
    return $pdf->stream('Data Tagihan Siswa.pdf');
}
    
    // public function export(Request $request)
    //     {
        
    //         return Excel::download(new TunggakanExport, 'tunggakan.xlsx');
    //     }
    public function export(Request $request)
    {
        $id_thn_ajaran = $request->input('id_thn_ajaran', null);
        $id_kelas = $request->input('id_kelas', null);

        return Excel::download(new TunggakanExport($id_thn_ajaran, $id_kelas), 'TagihanSiswa.xlsx');
    }
    }

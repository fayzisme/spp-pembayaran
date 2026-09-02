<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DetailPembayaran;
use App\Exports\LaporanPembayaranExport;
use App\Scopes\LulusScope;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class LaporanPembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //      // Mulai query dari DetailPembayaran
    // $details = DetailPembayaran::with(['siswa', 'jenisPembayaran', 'tarif.kelas', 'tahunAjaran', 'petugas']);

    // // Filter berdasarkan tahun ajaran
    // if ($request->filled('id_thn_ajaran')) {
    //     $details->where('id_thn_ajaran', $request->id_thn_ajaran);
    // }

    // // Join dengan tabel terkait untuk filter berdasarkan kelas
    // if ($request->filled('id_kelas')) {
    //     $details->whereHas('tarif.kelas', function ($query) use ($request) {
    //         $query->where('id_kelas', $request->id_kelas);
    //     });
    // }

    // // Lakukan pengambilan data dengan query yang sudah difilter
    // $details = $details->get();

    // // Ambil data untuk dropdown filter
    // $th_ajaran = DB::table('thn_ajaran')->select('id_thn_ajaran', 'thn_ajaran', 'semester')->get();
    // $kelas = DB::table('kelas')->select('id_kelas', 'tingkat', 'nama_kelas')->get();
    // // $jenis_pembayaran = DB::table('jenis_pembayaran')->select('id_jenis_pembayaran', 'tipe_bayar')->get();

    // return view('pages.laporan.laporan-pembayaran.index', [
    //     'details' => $details,
    //     'thn_ajaran' => $th_ajaran,
    //     'kelas' => $kelas
    //     // 'jenis_pembayaran' => $jenis_pembayaran,
    // ]);
    // }
    // public function index(Request $request)
    // {
    //     // Mulai query dari DetailPembayaran
    //     $details = DetailPembayaran::with(['siswa', 'jenisPembayaran', 'tarif.kelas', 'tahunAjaran', 'petugas']);

    //     // Filter berdasarkan tahun ajaran
    //     if ($request->filled('id_thn_ajaran')) {
    //         $details->where('id_thn_ajaran', $request->id_thn_ajaran);
    //     }

    //     // Join dengan tabel terkait untuk filter berdasarkan kelas
    //     if ($request->filled('id_kelas')) {
    //         $details->whereHas('tarif.kelas', function ($query) use ($request) {
    //             $query->where('id_kelas', $request->id_kelas);
    //         });
    //     }

    //     // Tambahkan filter untuk menyertakan siswa yang lulus atau aktif
    //     $details->whereHas('siswa', function ($query) {
    //         $query->whereIn('status', [0, 1]); // Menyertakan siswa dengan status aktif atau lulus
    //     });

    //     // Lakukan pengambilan data dengan query yang sudah difilter
    //     $details = $details->get();

    //     // Ambil data untuk dropdown filter
    //     $th_ajaran = DB::table('thn_ajaran')->select('id_thn_ajaran', 'thn_ajaran', 'semester')->get();
    //     $kelas = DB::table('kelas')->select('id_kelas', 'tingkat', 'nama_kelas')->get();

    //     return view('pages.laporan.laporan-pembayaran.index', [
    //         'details' => $details,
    //         'thn_ajaran' => $th_ajaran,
    //         'kelas' => $kelas,
    //     ]);
    // }

    public function index(Request $request)
    {
        // Mulai query dari DetailPembayaran
        $details = DetailPembayaran::with(['siswa' => function($query){
            return $query->withoutGlobalScope(LulusScope::class);
        }, 'jenisPembayaran', 'tarif.kelas', 'tahunAjaran', 'petugas']);

        // Filter berdasarkan tahun ajaran
        if ($request->filled('id_thn_ajaran')) {
            $details->where('id_thn_ajaran', $request->id_thn_ajaran);
        }

        // Filter berdasarkan kelas
        if ($request->filled('id_kelas')) {
            $details->whereHas('tarif.kelas', function ($query) use ($request) {
                $query->where('id_kelas', $request->id_kelas);
            });
        }

        // Ambil data transaksi
        $details = $details->orderBy('created_at', 'desc')->get();

        // Ambil data untuk dropdown filter
        $th_ajaran = DB::table('thn_ajaran')->select('id_thn_ajaran', 'thn_ajaran', 'semester')->get();
        $kelas = DB::table('kelas')->select('id_kelas', 'tingkat', 'nama_kelas')->get();

        return view('pages.laporan.laporan-pembayaran.index', [
            'details' => $details,
            'thn_ajaran' => $th_ajaran,
            'kelas' => $kelas,
        ]);
    }


    public function exportPDF(Request $request)
{
    // Inisialisasi query tanpa Global Scope LulusScope
    $details = DetailPembayaran::withoutGlobalScope(LulusScope::class)
        ->whereHas('siswa', function ($query) {
            $query->whereIn('status', [0, 1]);
        });

    // Filter berdasarkan tahun ajaran
    if ($request->filled('id_thn_ajaran')) {
        $details->where('id_thn_ajaran', $request->id_thn_ajaran);
    }

    // Filter berdasarkan kelas
    if ($request->filled('id_kelas')) {
        $details->whereHas('tarif.kelas', function ($query) use ($request) {
            $query->where('id_kelas', $request->id_kelas);
        });
    }

    // Lakukan pengambilan data dengan query yang sudah difilter
    $details = $details->get();

    // Menggunakan Dompdf untuk mengkonversi view ke PDF
    $pdf = PDF::loadView('eksporpdf.laporan_pembayaran', compact('details'))->setPaper('a4', 'landscape');

    // Stream PDF ke user
    return $pdf->stream('Laporan Pembayaran.pdf');
}


    


    // public function exportExcel()
    // {
    //     return Excel::download(new LaporanPembayaranExport, 'laporan_pembayaran.xlsx');
    // }
    
    public function exportExcel(Request $request)
{
    $details = DetailPembayaran::query();

    if ($request->filled('id_thn_ajaran')) {
        $details->where('id_thn_ajaran', $request->id_thn_ajaran);
    }

    if ($request->filled('id_kelas')) {
        $details->whereHas('tarif.kelas', function ($query) use ($request) {
            $query->where('id_kelas', $request->id_kelas);
        });
    }

    $export = new LaporanPembayaranExport($details->get());

    return Excel::download($export, 'Laporan Pembayaran.xlsx');
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

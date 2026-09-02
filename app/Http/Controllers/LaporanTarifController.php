<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisPembayaran;
use App\Models\TahunAjaran;
use App\Models\Tarif;
use App\Models\Kelas;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanTarifExport;

class LaporanTarifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // Mulai query dari JenisPembayaran
    $query = JenisPembayaran::with(['tarif.kelas', 'tahunAjaran']);

    // Filter berdasarkan tahun ajaran
    if ($request->filled('id_thn_ajaran')) {
        $query->whereHas('tahunAjaran', function ($q) use ($request) {
            $q->where('id_thn_ajaran', $request->id_thn_ajaran);
        });
    }

    // Mendapatkan data jenis pembayaran dengan filter tahun ajaran (jika ada)
    $jenisPembayaran = $query->get();

    // Mendapatkan semua data tahun ajaran
    $thn_ajaran = TahunAjaran::all();

    // Mengembalikan view 'laporan_tarif' dengan data jenis pembayaran dan tahun ajaran.
    return view('pages.laporan.laporan-tarif.index', compact('thn_ajaran', 'jenisPembayaran'));
}



    //keluaran sesuai select
    public function getData(Request $request)
    {
        $id_thn_ajaran = $request->get('id_thn_ajaran');
        $jenisPembayaran = JenisPembayaran::with(['tarif.kelas', 'tahunAjaran'])
            ->where('id_thn_ajaran', $id_thn_ajaran)
            ->get();

        return response()->json($jenisPembayaran);
    }

    // public function exportPDF()
    // {
    //     $jenisPembayaran = JenisPembayaran::with(['tarif.kelas', 'tahunAjaran'])->get();

    //     $pdf = PDF::loadView('eksporpdf.laporan_tarif', compact('jenisPembayaran'));
    //     return $pdf->setPaper('a4', 'landscape')->download('laporan_tarif.pdf');
    // }

    // public function exportPDF(Request $request)
    // {
    //     $id_thn_ajaran = $request->get('id_thn_ajaran');
    //     $query = JenisPembayaran::with(['tarif.kelas', 'tahunAjaran']);

    //     if ($id_thn_ajaran) {
    //         $query->whereHas('tahunAjaran', function ($query) use ($id_thn_ajaran) {
    //             $query->where('id_thn_ajaran', $id_thn_ajaran);
    //         });

    //         if ($query->count() == 0) {
    //             return redirect()->back()->with('error', 'Data tidak ditemukan');
    //         }
    //     }

    //     $jenisPembayaran = $query->get();

    //     $pdf = PDF::loadView('eksporpdf.laporan_tarif', compact('jenisPembayaran'));
    //     return $pdf->setPaper('a4', 'landscape')->download('laporan_tarif.pdf');
    // }

    public function exportPDF(Request $request)
    {
        $jenisPembayaran = JenisPembayaran::with(['tarif', 'tahunAjaran'])->get();
        
        if ($request->has('id_thn_ajaran') && $request->id_thn_ajaran != '') {
            $jenisPembayaran = JenisPembayaran::whereHas('tahunAjaran', function ($query) use ($request) {
                $query->where('id_thn_ajaran', $request->id_thn_ajaran);
            })->with(['tarif', 'tahunAjaran'])->get();
        }
    
        $pdf = PDF::loadView('eksporpdf.laporan_tarif', compact('jenisPembayaran'))->setPaper('a4', 'landscape');
        return $pdf->stream('Laporan Tarif Pembayaran.pdf');
    }

    // public function exportExcel(Request $request)
    // {
    //     $id_thn_ajaran = $request->get('id_thn_ajaran');
    //     $query = JenisPembayaran::with(['tarif.kelas', 'tahunAjaran']);

    //     if ($id_thn_ajaran) {
    //         $query->whereHas('tahunAjaran', function ($query) use ($id_thn_ajaran) {
    //             $query->where('id_thn_ajaran', $id_thn_ajaran);
    //         });

    //         if ($query->count() == 0) {
    //             return redirect()->back()->with('error', 'Data tidak ditemukan');
    //         }
    //     }

    //     $jenisPembayaran = $query->get();

    //     return Excel::download(new LaporanTarifExport($jenisPembayaran), 'laporan_tarif.xlsx');
    // }
    public function exportExcel(Request $request)
    {
        $jenisPembayaran = JenisPembayaran::with(['tarif', 'tahunAjaran']);
    
        if ($request->has('id_thn_ajaran') && $request->id_thn_ajaran != '') {
            $jenisPembayaran->whereHas('tahunAjaran', function ($query) use ($request) {
                $query->where('id_thn_ajaran', $request->id_thn_ajaran);
            });
        }
    
        $jenisPembayaran = $jenisPembayaran->get();
    
        return Excel::download(new LaporanTarifExport($jenisPembayaran), 'laporan_tarif_pembayaran.xlsx');
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

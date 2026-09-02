<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanTotalController extends Controller
{
    public function index()
{
    $laporantotal = DB::table('tahun_ajaran')
        ->join('jenis_pembayaran', 'tahun_ajaran.id', '=', 'jenis_pembayaran.id_tahun_ajaran')
        ->join('tarif', 'jenis_pembayaran.id', '=', 'tarif.id_jenis_pembayaran')
        ->join('kelas', 'tarif.id_kelas', '=', 'kelas.id')
        ->join('siswa', 'kelas.id', '=', 'siswa.id_kelas')
        ->leftJoin('transaksi', function($join) {
            $join->on('siswa.id', '=', 'transaksi.id_siswa')
                 ->on('jenis_pembayaran.id', '=', 'transaksi.id_jenis_pembayaran');
        })
        ->leftJoin('detail_transaksi', function($join) {
            $join->on('transaksi.id', '=', 'detail_transaksi.id_transaksi');
        })
        ->select(
            'tahun_ajaran.thn_ajaran',
            'tahun_ajaran.semester',
            'jenis_pembayaran.nama_pembayaran',
            'tarif.tarif',
            'kelas.nama_kelas',
            'kelas.tingkat',
            DB::raw('COUNT(siswa.id) as jumlah_siswa'),
            DB::raw('SUM(transaksi.total_bayar) as total_bayar'),
            DB::raw('SUM(detail_transaksi.jumlah_transaksi) as sudah_dibayarkan')
        )
        ->groupBy(
            'tahun_ajaran.thn_ajaran',
            'tahun_ajaran.semester',
            'jenis_pembayaran.nama_pembayaran',
            'tarif.tarif',
            'kelas.nama_kelas',
            'kelas.tingkat'
        )
        ->get();

    foreach ($laporantotal as $item) {
        $item->sisa_tanggungan = $item->total_bayar - $item->sudah_dibayarkan;
    }

    return view('pages.laporan.laporan-total.index', compact('laporan'));
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

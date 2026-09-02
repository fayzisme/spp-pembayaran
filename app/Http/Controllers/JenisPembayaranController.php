<?php

namespace App\Http\Controllers;

use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use App\Models\Tarif;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class JenisPembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisPembayaran = JenisPembayaran::paginate(10);
        $tahunAjaran = TahunAjaran::all();
        // $semester = Semester::all();


        return view('pages.jenis-transaksi.index', compact('jenisPembayaran', 'tahunAjaran'));
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
    // public function store(Request $request)
    // {
        
    //     $request->validate([
    //         // 'nama_pembayaran' =>'required|unique:jenis_pembayaran,nama_pembayaran',
    //         'nama_pembayaran' =>'required',
    //         'id_thn_ajaran' =>'required|exists:thn_ajaran,id_thn_ajaran',
    //         'tipe_bayar' => 'required',
    //     ]);

    //     $jenisPembayaranCreate = JenisPembayaran::create($request->all());

    //     if (!$jenisPembayaranCreate) {
    //         Alert::error('Error', 'Data gagal ditambahkan');
            
    //         return redirect()->route('jenis-transaksi.index');
    //     }

    //     Alert::success('Berhasil', 'Data berhasil ditambahkan');
        
    //     return redirect()->route('jenis-transaksi.index');
    // }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_pembayaran' => 'required',
            'id_thn_ajaran' => 'required|exists:thn_ajaran,id_thn_ajaran',
            'tipe_bayar' => 'required',
        ]);

        // Check if the combination of nama_pembayaran and id_thn_ajaran is unique
        if (JenisPembayaran::where('nama_pembayaran', $request->nama_pembayaran)
                ->where('id_thn_ajaran', $request->id_thn_ajaran)
                ->exists()) {
            // Jika data duplikat ditemukan, tampilkan SweetAlert
            Alert::error('Gagal', 'Data sudah ada!');
            return redirect()->route('jenis-transaksi.index');
        }

        // Jika validasi lolos dan tidak ada duplikat, simpan data baru
        JenisPembayaran::create($request->all());

        // Tampilkan SweetAlert berhasil
        Alert::success('Berhasil', 'Data berhasil ditambahkan');
        return redirect()->route('jenis-transaksi.index');
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
    // public function update(Request $request, string $id)
    // {
    //     $request->validate([
    //         'nama_pembayaran' => [
    //             'required',
    //             Rule::unique('jenis_pembayaran')->ignore($id, 'id_jenis_pembayaran')
    //         ],
    //         // 'nama_transaksi' =>'required|exists:jenis_transaksi,id_jenis_transaksi',
    //         'id_thn_ajaran' =>'required|exists:thn_ajaran,id_thn_ajaran',
    //         'tipe_bayar' => 'required',
    //     ]);

    //     $jenisPembayaran = JenisPembayaran::find($id);

    //     if (!$jenisPembayaran) {
    //         Alert::error('Error', 'Data jenis transaksi tidak ditemukan');
            
    //         return redirect()->route('jenis-transaksi.index');
    //     }
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'nama_pembayaran' => 'required',
            'id_thn_ajaran' => 'required|exists:thn_ajaran,id_thn_ajaran',
            'tipe_bayar' => 'required',
        ]);
    
        // Cek jika kombinasi nama_pembayaran dan id_thn_ajaran sudah ada, kecuali record yang sedang diperbarui
        $duplicate = JenisPembayaran::where('nama_pembayaran', $request->nama_pembayaran)
                                    ->where('id_thn_ajaran', $request->id_thn_ajaran)
                                    ->where('id_jenis_pembayaran', '!=', $id)
                                    ->exists();
    
        if ($duplicate) {
            // Jika data duplikat ditemukan, tampilkan SweetAlert
            Alert::error('Gagal', 'Data sudah ada!');
            return redirect()->route('jenis-transaksi.index');
        }
    
        // Cari record berdasarkan ID
        $jenisPembayaran = JenisPembayaran::find($id);
        if (!$jenisPembayaran) {
            // Jika record tidak ditemukan, tampilkan SweetAlert
            Alert::error('Gagal', 'Data jenis transaksi tidak ditemukan!');
            return redirect()->route('jenis-transaksi.index');
        }

        $jenisPembayaran->update($request->all());

        #:: Proses Update total_bayar di table transaksi
        #:: -------------------------------------------------
        $databaru = JenisPembayaran::find($id);
        // cari tarif terkait
        $dataTarif = DB::table('tarif')->where('id_jenis_pembayaran', $databaru->id_jenis_pembayaran)->get();
        
        if ($dataTarif != null) {
            // cek tipe: Bulanan || Bebas
            if ($databaru->tipe_bayar == 'Bulanan') {
                foreach ($dataTarif as $itemTarif) {
                    // ambil kelas terkait
                    $kelas = DB::table('kelas')->where('id_kelas', $itemTarif->id_kelas)->first();
                    $dataKelas[] = $kelas;
                    $totKelas = count($dataKelas);

                    // ambil tarif dasar
                    $tarifSpesifik[] = $itemTarif->tarif;

                    for ($i=0; $i < $totKelas; $i++) { 
                        // hitung total bayar
                        $totalBayar_baru = $tarifSpesifik[$i] * 6;
                        #:: Update
                        DB::table('transaksi')->where('id_kelas', $dataKelas[$i]->id_kelas)->where('id_tarif', $itemTarif->id_tarif)->update(['total_bayar' => $totalBayar_baru]);
                    }
                }
            } else {
                foreach ($dataTarif as $itemTarif) {
                    // ambil kelas terkait
                    $kelas = DB::table('kelas')->where('id_kelas', $itemTarif->id_kelas)->first();
                    $dataKelas[] = $kelas;
                    $totKelas = count($dataKelas);

                    // ambil tarif dasar
                    $tarifSpesifik[] = $itemTarif->tarif;

                    for ($i=0; $i < $totKelas; $i++) { 
                        // hitung total bayar
                        $totalBayar_baru = $tarifSpesifik[$i];
                        #:: Update
                        DB::table('transaksi')->where('id_kelas', $dataKelas[$i]->id_kelas)->where('id_tarif', $itemTarif->id_tarif)->update(['total_bayar' => $totalBayar_baru]);
                    }
                }
            }
        }
        #:: -------------------------------------------------

        Alert::success('Berhasil', 'Data berhasil diubah');
        
        return redirect()->route('jenis-transaksi.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jenisPembayaran = JenisPembayaran::find($id);

        if (!$jenisPembayaran) {
            Alert::error('Error', 'Data jenis transaksi tidak ditemukan');
            
            return redirect()->route('jenis-transaksi.index');
        }

        Tarif::where('id_jenis_pembayaran', $jenisPembayaran->id_jenis_pembayaran)->delete();
        $jenisPembayaran->delete();

        Alert::success('Berhasil', 'Data berhasil dihapus');
        
        return redirect()->route('jenis-transaksi.index');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use RealRashid\SweetAlert\Facades\Alert;

class KelulusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::where('tingkat', 'IX')->get();
        $tahunAjaran = TahunAjaran::all();
        $siswa = Siswa::has('transaksi')->whereHas('kelas', function($query){
            return $query->where('tingkat', 'IX');
        })->with(['kelas', 'tahunAjaran', 'transaksi' => function($query){
            return $query->where('status','!=' ,'lunas');
        }])->paginate(100);

       $unpaidExists = false;
        foreach ($siswa as $s) {
            if ($s->transaksi->count() > 0) {
                $unpaidExists = true;
                break;
            }
        }
        
        return view('pages.arsip.kelulusan.index', compact('kelas', 'tahunAjaran', 'siswa', 'unpaidExists'));
    }

    public function getSiswa(Request $request)
    {
        $kelasAsal = $request->query('kelas');
        $tahunAjaranAsal = $request->query('tahunAjaran');

        $siswa = Siswa::whereHas('transaksi', function($query) use ($kelasAsal, $tahunAjaranAsal){
            return $query->where('id_kelas', $kelasAsal)->where('id_thn_ajaran', $tahunAjaranAsal);
        })->with(['kelas', 'tahunAjaran','transaksi' => function ($query){
            return $query->where('status', '!=', 'Lunas');
        }])
        // ->where('id_kelas', $kelasAsal)
        // ->where('id_thn_ajaran', $tahunAjaranAsal)
        ->get();

        return response()->json(['siswa' => $siswa]);
    }


    // public function index()
    // {
    //     $kelas = Kelas::all();
    //     $tahunAjaran = TahunAjaran::all();
    //     $siswa = Siswa::latest()->paginate(10);

    //     return view('pages.arsip.kelulusan.index', compact('kelas', 'tahunAjaran', 'siswa'));
    // }

    public function naikKelas(Request $request)
    {
        // Validasi request
        $request->validate([
            'id_kelas_asal' => 'required',
            'id_thn_ajaran_asal' => 'required',
            'selected_siswa' => 'required|array|min:1', // Pastikan setidaknya satu siswa dipilih
            'selected_siswa.*' => 'exists:siswas,id_siswa', // Validasi setiap siswa yang dipilih ada di tabel siswa
        ]);
    
        // Cek apakah ada pembayaran yang belum lunas di kelas dan tahun ajaran asal
        $unpaidExists = Pembayaran::where('id_kelas', $request->id_kelas_asal)
            ->where('id_thn_ajaran', $request->id_thn_ajaran_asal)
            ->where('status', '!=', 'Lunas')
            ->exists();
    
        if ($unpaidExists) {
            return response()->json(['success' => false, 'message' => 'Tidak bisa melakukan kenaikan kelas, karena masih ada siswa yang belum melunasi pembayaran'], 400);
        }
    
        // Dapatkan siswa yang dipilih
        $siswaIds = $request->input('selected_siswa');
        $siswa = Siswa::whereIn('id_siswa', $siswaIds)->get();
    
        if ($siswa->count() == 0) {
            return response()->json(['success' => false, 'message' => 'Tidak ada siswa yang ditemukan'], 404);
        }
    
        // Proses kenaikan kelas
        foreach ($siswa as $s) {
            //status 1 = lulus
            $s->status = 1;
            $s->save();
        }
    
        return response()->json(['success' => true, 'message' => 'Kelulusan siswa berhasil'], 200);
    }

    public function checkUnpaid(Request $request)
{
    $id_kelas_asal = $request->query('kelas');
    $id_thn_ajaran_asal = $request->query('tahunAjaran');

    $unpaidExists = Pembayaran::where('id_kelas', $id_kelas_asal)
        ->where('id_thn_ajaran', $id_thn_ajaran_asal)
        ->where('status', '!=', 'Lunas')
        ->exists();

    return response()->json(['unpaidExists' => $unpaidExists]);
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

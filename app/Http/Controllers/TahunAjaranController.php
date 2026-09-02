<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TahunAjaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahunAjaran = TahunAjaran::all();

        return view('pages.data.tahun-ajaran.index', compact('tahunAjaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.data.tahun-ajaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //         // Validasi input
    //     $request->validate([
    //         'tahun_ajaran_mulai' => 'required|numeric|digits:4',
    //         'tahun_ajaran_selesai' => 'required|numeric|digits:4',
    //         'semester' => 'required',
    //     ]);

    //     // Format tahun ajaran
    //     $thn_ajaran = $request->tahun_ajaran_mulai . '/' . $request->tahun_ajaran_selesai;

    //     // Check if the combination of thn_ajaran and semester is unique
    //     if (TahunAjaran::where('thn_ajaran', $thn_ajaran)
    //                 ->where('semester', $request->semester)
    //                 ->exists()) {
    //         // Jika data duplikat ditemukan, tampilkan SweetAlert
    //         Alert::error('Gagal', 'Tahun ajaran dan semester sudah ada!');
    //         return redirect()->route('tahun-ajaran.index');
    //     }

    //     // Simpan data baru
    //     TahunAjaran::create([
    //         'thn_ajaran' => $thn_ajaran,
    //         'semester' => $request->semester,
    //     ]);

    //     // Tampilkan SweetAlert berhasil
    //     Alert::success('Berhasil', 'Tahun ajaran berhasil ditambahkan');
    //     return redirect()->route('tahun-ajaran.index');
    // }

    public function store(Request $request)
    {
        
        $generateTahunAjaran = $this->generateData();

        if ($generateTahunAjaran) {
            // Tampilkan SweetAlert berhasil
            Alert::success('Berhasil', 'Tahun ajaran berhasil ditambahkan');
            return redirect()->route('tahun-ajaran.index');
        }
        else{
            // Jika data duplikat ditemukan, tampilkan SweetAlert
            Alert::error('Gagal', 'Tahun ajaran dan semester sudah ada!');
            return redirect()->route('tahun-ajaran.index');
        }
    }

    public function generateData(){
        $currentMonth = date('n'); // Mendapatkan bulan saat ini
        $currentYear = date('Y');  // Mendapatkan tahun saat ini

        $semester = '';
        $thn_ajaran = '';

        if ($currentMonth >= 1 && $currentMonth <= 6) {
            // Januari hingga Juni (Semester Genap)
            $semester = 'Genap';
            $thn_ajaran = ($currentYear - 1) . '/' . $currentYear;
        } else {
            // Juli hingga Desember (Semester Ganjil)
            $semester = 'Ganjil';
            $thn_ajaran = $currentYear . '/' . ($currentYear + 1);
        }

         // Check if the combination of thn_ajaran and semester is unique
         if (TahunAjaran::where('thn_ajaran', $thn_ajaran)
                ->where('semester', $semester)
                ->exists()) 
            {
                return false;
            }

            // Simpan data baru
            TahunAjaran::create([
            'thn_ajaran' => $thn_ajaran,
            'semester' => $semester,
            ]);

            return true;

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
        $tahunAjaran = TahunAjaran::find($id);
        $tahunAjaran->tahun_ajaran_mulai = substr($tahunAjaran->thn_ajaran, 0, 4);
        $tahunAjaran->tahun_ajaran_selesai = substr($tahunAjaran->thn_ajaran, 5, 4);

        return view('pages.data.tahun-ajaran.edit', compact('tahunAjaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
            // Validasi input
        $request->validate([
            'tahun_ajaran_mulai' => 'required|numeric|digits:4',
            'tahun_ajaran_selesai' => 'required|numeric|digits:4',
            'semester' => 'required',
        ]);

        // Format tahun ajaran
        $thn_ajaran = $request->tahun_ajaran_mulai . '/' . $request->tahun_ajaran_selesai;

        // Check if the combination of thn_ajaran and semester is unique, ignoring the current record
        $duplicate = TahunAjaran::where('thn_ajaran', $thn_ajaran)
                                ->where('semester', $request->semester)
                                ->where('id_thn_ajaran', '!=', $id)
                                ->exists();

        if ($duplicate) {
            // Jika data duplikat ditemukan, tampilkan SweetAlert
            Alert::error('Gagal', 'Tahun ajaran dan semester sudah ada!');
            return redirect()->route('tahun-ajaran.index');
        }

        // Cari record berdasarkan ID
        $tahunAjaran = TahunAjaran::find($id);
        if (!$tahunAjaran) {
            // Jika record tidak ditemukan, tampilkan SweetAlert
            Alert::error('Gagal', 'Data tidak ditemukan!');
            return redirect()->route('tahun-ajaran.index');
        }

        // Update data
        $tahunAjaran->update([
            'thn_ajaran' => $thn_ajaran,
            'semester' => $request->semester,
        ]);

        // Tampilkan SweetAlert berhasil
        Alert::success('Berhasil', 'Tahun ajaran berhasil diubah');
        return redirect()->route('tahun-ajaran.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        TahunAjaran::destroy($id);

        Alert::success('Berhasil', 'Tahun ajaran berhasil dihapus');

        return redirect()->route('tahun-ajaran.index');
    }
}

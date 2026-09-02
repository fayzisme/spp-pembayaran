<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::all();

        return view('pages.data.kelas.index', compact('kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.data.kelas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            // Validasi input
        $request->validate([
            'tingkat' => 'required',
            'nama_kelas' => 'required'
        ]);

        // Check if the combination of tingkat and nama_kelas is unique
        if (Kelas::where('tingkat', $request->tingkat)
                ->where('nama_kelas', $request->nama_kelas)
                ->exists()) {
            // Jika data duplikat ditemukan, tampilkan SweetAlert
            Alert::error('Gagal', 'Data sudah ada!');
            return redirect()->route('kelas.index');
        }

        // Jika validasi lolos dan tidak ada duplikat, simpan data baru
        Kelas::create($request->all());

        // Tampilkan SweetAlert berhasil
        Alert::success('Berhasil', 'Data berhasil ditambahkan');
        return redirect()->route('kelas.index');
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
        // $kelas = Kelas::where('id_kelas', $id)->first();
        $kelas = Kelas::find($id);

        return view('pages.data.kelas.edit', compact('kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       // Validasi input
    $request->validate([
        'tingkat' => 'required',
        'nama_kelas' => 'required'
    ]);

    // Check if the combination of tingkat and nama_kelas is unique, ignoring the current record
    $duplicate = Kelas::where('tingkat', $request->tingkat)
                      ->where('nama_kelas', $request->nama_kelas)
                      ->where('id_kelas', '!=', $id)
                      ->exists();

    if ($duplicate) {
        // Jika data duplikat ditemukan, tampilkan SweetAlert
        Alert::error('Gagal', 'Data sudah ada!');
        return redirect()->route('kelas.index');
    }

    // Cari record berdasarkan ID
    $kelas = Kelas::find($id);
    if (!$kelas) {
        // Jika record tidak ditemukan, tampilkan SweetAlert
        Alert::error('Gagal', 'Data tidak ditemukan!');
        return redirect()->route('kelas.index');
    }

    // Update data
    $kelas->update($request->all());

    // Tampilkan SweetAlert berhasil
    Alert::success('Berhasil', 'Data berhasil diubah');
    return redirect()->route('kelas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $kelas = Kelas::where('id_kelas', $id)->first();
        $kelas = Kelas::find($id);

        if (!$kelas) {
            Alert::error('Error', 'Data tidak ditemukan');
            return redirect()->route('kelas.index');
        }

        $kelas->delete();
        Alert::success('Berhasil', 'Data berhasil dihapus');
        return redirect()->route('kelas.index');
    }

}

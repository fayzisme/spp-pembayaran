<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Scopes\LulusScope;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $siswa = Siswa::query()->withoutGlobalScope(LulusScope::class)
            ->where('status', 1)
            ->where('nama', 'like', "%{$search}%")
            ->paginate(100);
        return view('pages.arsip.alumni.index', compact('siswa', 'search'));
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

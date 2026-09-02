<?php

namespace App\Http\Controllers;

use App\Models\NamaPembayaran;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class NamaPembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $namaPembayaran = NamaPembayaran::paginate(10);

    //     return view('pages.keuangan.nama-transaksi.index', compact('namaPembayaran'));
    // }

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
    //         'nama_transaksi' =>'required|string|max:255',
    //         'keterangan' =>'required|string|max:255',
    //     ]);

    //     NamaPembayaran::create($request->all());

    //     Alert::success('Success', 'Nama transaksi berhasil ditambahakan');

    //     return redirect()->route('nama-transaksi.index');
    // }

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
    //         'nama_transaksi' =>'required|string|max:255',
    //         'keterangan' =>'required|string|max:255',
    //     ]);

    //     NamaPembayaran::find($id)->update($request->all());

    //     Alert::success('Success, Nama transaksi berhasil diubah');

    //     return redirect()->route('nama-transaksi.index');
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     NamaPembayaran::destroy($id);

    //     Alert::success('Success', 'Nama transaksi berhasil dihapus');

    //     return redirect()->route('nama-transaksi.index');
    // }
}

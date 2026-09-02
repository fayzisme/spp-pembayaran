<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JenisPembayaran;
use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Tarif;
use RealRashid\SweetAlert\Facades\Alert;

class TarifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        $tarif = Tarif::where('id_jenis_pembayaran', $id)->get();
        $jenisPembayaran = JenisPembayaran::find($id);
        $kelas = Kelas::all();

        return view('pages.tarif.index', compact('tarif', 'jenisPembayaran', 'kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {
        $jenisPembayaran = JenisPembayaran::find($id);
        $kelasOptions = Kelas::all();

        if (!$jenisPembayaran) {
            Alert::error('Error', 'Data jenis pembayaran tidak ditemukan');
            return redirect()->route('jenis-pembayaran.index');
        }

        if ($jenisPembayaran) {
            // filter AlreadyAddedKelas on Jenispembayaran return kelasOptions and filter alreadyAddedKelas
            $alreadyAddedKelas = $jenisPembayaran->tarif->pluck('id_kelas')->toArray();
            $kelasOptions = $kelasOptions->filter(function ($kelas) use ($alreadyAddedKelas) {
                return !in_array($kelas->id_kelas, $alreadyAddedKelas);
            });

            return view('pages.tarif.create', compact('jenisPembayaran', 'kelasOptions', 'alreadyAddedKelas'));
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(string $id, Request $request)
    {
        $request->validate([
            'kelas' => 'required|array',
            'tarif' => 'required|numeric',
        ]);

        $kelasIds = $request->kelas;
        $tarifValue = $request->tarif;

        foreach ($kelasIds as $kelasId) {
            $tarif = Tarif::create([
                'id_jenis_pembayaran' => $id,
                'id_kelas' => $kelasId,
                'tarif' => $tarifValue,
            ]);

            if (!$tarif) {
                Alert::error('Error', 'Data tarif gagal ditambahkan untuk kelas ID: ' . $kelasId);
                return redirect()->route('tarif.index', $id);
            }

            #:: cari tipe pembayaran untuk klasifikasi apakah bulanan atau bebas
            $tipePembayaran = JenisPembayaran::find($tarif->id_jenis_pembayaran);
            if ($tipePembayaran->tipe_bayar == 'Bulanan') {
                $totBayar = $tarif->tarif * 6;
            } else {
                $totBayar = $tarif->tarif;
            }

            $kelas = Kelas::find($kelasId);
            $siswa = $kelas->siswa;

            foreach ($siswa as $item) {
                $transaksi = Pembayaran::create([
                    'id_kelas' => $kelasId,
                    'id_tarif' => $tarif->id_tarif,
                    'id_siswa' => $item->id_siswa,
                    'id_jenis_pembayaran' => $id,
                    'id_thn_ajaran' => $tarif->jenisPembayaran->id_thn_ajaran,
                    'invoice' => 'INV-' . time(),
                    'total_bayar' => $totBayar,
                    'status' => 'Belum Lunas',
                ]);

                if (!$transaksi) {
                    Alert::error('Error', 'Data transaksi gagal ditambahkan untuk siswa: ' . $item->nama);
                    return redirect()->route('tarif.index', $id);
                }
            }
        }

        Alert::success('Berhasil', 'Data tarif berhasil ditambahkan');
        return redirect()->route('tarif.index', $id);
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
    public function edit(string $id, string $id_tarif)
    {
        $tarif = Tarif::find($id_tarif);
        $jenisPembayaran = JenisPembayaran::find($id);
        $kelas = Kelas::all();

        return view('pages.tarif.edit', compact('tarif', 'jenisPembayaran', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, string $id_tarif)
    {
        // dd($id_tarif);
        $request->validate([
            'id_kelas' => 'required',
            'tarif' => 'required',
        ]);

        $tarif = Tarif::find($id_tarif);

        if (!$tarif) {
            Alert::error('Error', 'Data tarif tidak ditemukan');
            return redirect()->route('tarif.index', $id);
        }

        #:: cari tipe pembayaran untuk klasifikasi apakah bulanan atau bebas
        $tipePembayaran = JenisPembayaran::find($tarif->id_jenis_pembayaran);
        if ($tipePembayaran->tipe_bayar == 'Bulanan') {
            $totBayar = $request->tarif * 6;
        } else {
            $totBayar = $request->tarif;
        }

        $tarif->update([
            'id_kelas' => $request->id_kelas,
            'tarif' => $request->tarif,
        ]);

        #:: cari kelas terkait tarif ini
        $kelas = Kelas::find($tarif->id_kelas);
        $siswa = $kelas->siswa;
        // update total bayar di setiap transaksi mahasiswa
        foreach ($siswa as $item) {
            DB::table('transaksi')->where('id_tarif', $id_tarif)->update(['total_bayar' => $totBayar]);
        }
        
        Alert::success('Berhasil', 'Data tarif berhasil diubah');

        return redirect()->route('tarif.index', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, string $id_tarif)
    {
        $tarif = Tarif::find($id_tarif);

        if (!$tarif) {
            Alert::error('Error', 'Data tarif tidak ditemukan');
            return redirect()->route('tarif.index', $id);
        }

        $tarif->delete();
        Alert::success('Berhasil', 'Data tarif berhasil dihapus');

        return redirect()->route('tarif.index', $id);

    }

    // public function showForm()
    // {
    //     $kelas = Kelas::all();
    //     return view('form-view', compact('kelas'));
    // }

}

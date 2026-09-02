<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Petugas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Scopes\LulusScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $siswa = $request->input('id_siswa');
        $thn_ajaran = $request->input('id_thn_ajaran');
        $kelas = Kelas::all();
        $tahunAjaran = TahunAjaran::all();
        $siswas = Siswa::with('kelas')->get();
        $informasi_siswa = [];
        $transaksiBulanan = [];
        $transaksiLainnya = [];

        // $siswas = Siswa::with('kelas')
        // ->where(function ($query) {
        //     $query->where('status', '!=', 1)
        //         ->orWhere('status', 1); // Menyertakan siswa yang sudah lulus (status 1)
        // })
        // ->get();
    
        if (Auth::user()->id_role != 3) {
            if ($siswa && $thn_ajaran) {
                // informasi siswa
                $informasi_siswa = Siswa::addSelect('nis', 'nama', 'id_user', 'id_kelas', 'no_hp', 'tgl_lahir', 'alamat')
                    ->where('id_siswa', $siswa)
                    ->with(['user', 'kelas'])
                    ->get()
                    ->toArray();
    
                $transaksiBulanan = Pembayaran::where('id_siswa', $siswa)
                    ->where('id_thn_ajaran', $thn_ajaran)
                    ->whereHas('jenisPembayaran', function ($query) {
                        $query->where('tipe_bayar', 'Bulanan');
                    })
                    ->with(['kelas', 'tarif', 'jenisPembayaran', 'tahunAjaran', 'detailPembayaran'])
                    ->get()
                    ->each(function ($transaksi) {
                        $transaksi->status = $transaksi->is_lunas ? 'Lunas' : 'Belum Lunas';
                    });
    
                $transaksiLainnya = Pembayaran::where('id_siswa', $siswa)
                    ->where('id_thn_ajaran', $thn_ajaran)
                    ->whereHas('jenisPembayaran', function ($query) {
                        $query->where('tipe_bayar', 'Bebas');
                    })
                    ->with(['kelas', 'tarif', 'jenisPembayaran', 'tahunAjaran', 'detailPembayaran'])
                    ->get()
                    ->each(function ($transaksi) {
                        $transaksi->status = $transaksi->tarif->tarif == $transaksi->jumlah_bayar ? 'Lunas' : 'Belum Lunas';
                    });
    
                Session::put('last_query', $request->query());
            }
        } else {
            $siswa = Siswa::withoutGlobalScope(LulusScope::class)->where('id_user', $userId)->first();
    
            if ($siswa) {
                // informasi siswa
                $informasi_siswa = Siswa::withoutGlobalScope(LulusScope::class)->addSelect('nis', 'nama', 'id_user', 'id_kelas', 'no_hp', 'tgl_lahir', 'alamat')
                    ->where('id_user', $userId)
                    ->with(['user', 'kelas'])
                    ->get()
                    ->toArray();
    
                $transaksiBulanan = Pembayaran::query();
                $transaksiLainnya = Pembayaran::query();
    
                if ($thn_ajaran) {
                    $transaksiBulanan = $transaksiBulanan->where('id_thn_ajaran', $thn_ajaran);
                    $transaksiLainnya = $transaksiLainnya->where('id_thn_ajaran', $thn_ajaran);
                }
    
                $transaksiBulanan = $transaksiBulanan->where('id_siswa', $siswa->id_siswa)
                    ->whereHas('jenisPembayaran', function ($query) {
                        $query->where('tipe_bayar', 'Bulanan');
                    })
                    ->with(['kelas', 'tarif', 'jenisPembayaran', 'tahunAjaran', 'detailPembayaran'])
                    ->orderBy('id_thn_ajaran', 'desc')
                    ->get()
                    ->each(function ($transaksi) {
                        $transaksi->status = $transaksi->is_lunas ? 'Lunas' : 'Belum Lunas';
                    });
    
                $transaksiLainnya = $transaksiLainnya->where('id_siswa', $siswa->id_siswa)
                    ->whereHas('jenisPembayaran', function ($query) {
                        $query->where('tipe_bayar', 'Bebas');
                    })
                    ->with(['kelas', 'tarif', 'jenisPembayaran', 'tahunAjaran', 'detailPembayaran'])
                    ->orderBy('id_thn_ajaran', 'desc')
                    ->get()
                    ->each(function ($transaksi) {
                        $transaksi->status = $transaksi->tarif->tarif == $transaksi->jumlah_bayar ? 'Lunas' : 'Belum Lunas';
                    });
            }
        }
    
        if ($informasi_siswa) {
            // Menghapus key yang tidak diperlukan dari setiap elemen dalam array $informasi_siswa
            $informasi_siswa = array_map(function ($item) {
                return [
                    'nis' => $item['nis'],
                    'nama_lengkap' => $item['nama'],
                    'kelas' => $item['kelas']['tingkat'] . ' ' . $item['kelas']['nama_kelas'], // Menggabungkan tingkat dan nama kelas
                    'no_hp' => $item['no_hp'],
                    'tgl_lahir' => \Carbon\Carbon::parse($item['tgl_lahir'])->format('d-m-Y'),
                    'alamat' => $item['alamat']
                ];
            }, $informasi_siswa);
        }
    
        // Mengambil semua siswa termasuk yang sudah lulus (status 1)
        $siswas = Siswa::withoutGlobalScope(LulusScope::class)->with(['kelas', 'tahunAjaran', 'transaksi' => function ($query) {
            $query->where('status', '!=', '1');
        }])
        ->where(function($query) {
            $query->where('status', '!=', '1') // Siswa aktif
                  ->orWhere('status', '1');   // Siswa yang sudah lulus
        })
        ->get();
    
        return view('pages.transaksi.index', compact('transaksiBulanan', 'transaksiLainnya', 'kelas', 'tahunAjaran', 'siswas', 'informasi_siswa'));
    }
    
    public function getSiswa(Request $request)
    {
        // Mengambil semua siswa termasuk yang sudah lulus (status 1)
        $siswa = Siswa::withoutGlobalScope(LulusScope::class)->with(['kelas', 'tahunAjaran', 'transaksi' => function ($query) {
            return $query->where('status', '!=', '1');
        }])
        ->orWhere('status', '1') // Menyertakan siswa yang sudah lulus (status 1)
        ->get();
    
        return response()->json(['siswa' => $siswa]);
    }
    
    

    
//     public function getSiswa(Request $request)
// {
//     $siswa = Siswa::with(['kelas', 'tahunAjaran', 'transaksi' => function ($query) {
//         $query->where('status', '!=', 1);
//     }])
//     ->orWhere('status', 1) // Menyertakan siswa yang sudah lulus (status 1)
//     ->get();

//     return response()->json(['siswa' => $siswa]);
// }

    

    // public function getSiswa(Request $request)
    // {
    //     // Mengambil semua siswa dan memuat hubungan terkait
    //     $siswa = Siswa::with(['kelas', 'tahunAjaran', 'transaksi' => function ($query) {
    //         // Menyertakan semua transaksi
    //         $query->where('status', '!=', '1');
    //     }])
    //     ->orWhere('status', '1') // Menyertakan siswa yang sudah lulus (status 1)
    //     ->get();

    //     return response()->json(['siswa' => $siswa]);
    // }



    public function show(string $id)
    {
        $userId = Auth::id();
        $transaksi = Pembayaran::where('id_transaksi', $id)->with(['tahunAjaran', 'tarif'])->first();

        if ($transaksi->jenisPembayaran->tipe_bayar == 'Bebas') {
            return redirect()->route('transaksi.showLain', $id);
        }

        $detailPembayaran = [];
        $alreadyPaidMonths = [];

        // Array bulan dalam bahasa Indonesia
        $bulanOptions = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $semesters = [
            'Ganjil' => [7, 8, 9, 10, 11, 12],
            'Genap' => [1, 2, 3, 4, 5, 6]
        ];

        if ($transaksi) {
            $bulanTemps = $semesters[$transaksi->tahunAjaran->semester];
            $bulanOptions = array_filter($bulanOptions, function ($key) use ($bulanTemps) {
                return in_array($key, $bulanTemps);
            }, ARRAY_FILTER_USE_KEY);

            $detailPembayaran = DetailPembayaran::where('id_transaksi', $id)
                ->whereIn('bulan', $bulanOptions)
                ->with(['transaksi', 'tarif', 'jenisPembayaran', 'tahunAjaran', 'siswa' => function ($query){
                    return $query->withoutGlobalScope(LulusScope::class);
                }])
                ->get();

            $alreadyPaidMonths = DetailPembayaran::where('id_transaksi', $id)
                ->where('status_transaksi', 'Sukses')
                ->pluck('bulan')
                ->toArray();

            $transaksi->status = $transaksi->is_lunas ? 'Lunas' : 'Belum Lunas';
            $transaksi->save();
        }

        //get data petugas
        $petugas = Petugas::all();

        return view('pages.transaksi.show', compact('detailPembayaran', 'transaksi', 'bulanOptions', 'alreadyPaidMonths', 'petugas'));
    }


    public function showLain(string $id)
    {
        $userId = Auth::id();

        $transaksi = Pembayaran::where('id_transaksi', $id)->first();

        if ($transaksi->jenisPembayaran->tipe_bayar == 'Bulanan') {
            return redirect()->route('transaksi.show', $id);
        }

        $detailPembayaran = [];

        if ($transaksi) {
            $detailPembayaran = DetailPembayaran::where('id_transaksi', $id)

                ->with(['transaksi', 'tarif', 'jenisPembayaran', 'tahunAjaran','siswa' => function($query){
                    return $query->withoutGlobalScope(LulusScope::class);
                }])
                ->get();

            $transaksi->status = $transaksi->tarif->tarif == $transaksi->jumlah_bayar ? 'Lunas' : 'Belum Lunas';
            $transaksi->save();
        }

        // get data petugas
        $petugas = Petugas::all();

        return view('pages.transaksi.show-lain', compact('detailPembayaran', 'transaksi', 'petugas'));
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

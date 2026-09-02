<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Tarif;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KenaikanKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::where('tingkat', '<>', 'IX')->get();
        $kelas_tujuan = Kelas::where('tingkat', '<>', 'VII')->get();
        $tahunAjaran = TahunAjaran::all();
        $siswa = Siswa::whereHas('kelas', function($query){
            return $query->where('tingkat','<>','IX');
        })
        ->with(['kelas', 'tahunAjaran','transaksi'])->latest()->paginate(10);

        $siswa->setCollection($siswa->getCollection()->map(function ($siswa) {
            $siswa->cek_transaksi = $siswa->transaksi->filter(function ($transaksi) use ($siswa) {
                return $transaksi->id_kelas == $siswa->id_kelas && $transaksi->id_thn_ajaran == $siswa->id_thn_ajaran;
            })->values()->all();

            return $siswa;
        })); 
        

        return view('pages.data.kenaikan-kelas.index', compact('kelas','kelas_tujuan','tahunAjaran', 'siswa'));
    }

    public function naikKelas(Request $request)
    {
        // Validasi request
        $request->validate([
            'id_kelas_asal' => 'required',
            'id_kelas_tujuan' => 'required',
            'id_thn_ajaran_asal' => 'required',
            'id_thn_ajaran_tujuan' => 'required',
            // 'selected_siswa' => 'required|array|min:1', // Pastikan setidaknya satu siswa dipilih
            // 'selected_siswa.*' => 'exists:siswas,id_siswa', // Validasi setiap siswa yang dipilih ada di tabel siswa
        ]);

            // Cek apakah ada pembayaran yang belum lunas di kelas dan tahun ajaran asal
            // $unpaidExists = Pembayaran::where('id_kelas', $request->id_kelas_asal)
            //     ->where('id_thn_ajaran', $request->id_thn_ajaran_asal)
            //     ->where('status', '!=', 'Lunas')
            //     ->exists();

            $request->merge([
                'kelas' => $request->id_kelas_asal,
                'tahunAjaran' => $request->id_thn_ajaran_asal,
            ]);

            $unpaidExists = false;
            $check = $this->checkUnpaid($request);
            $check = json_decode(json_encode($check));

            if ($unpaidExists) {
                Alert::error('Gagal', 'Tidak bisa melakukan kenaikan kelas, karena masih ada siswa yang belum melunasi pembayaran');
                return redirect()->route('kenaikan-kelas.index');
            }
    
            $siswa = Siswa::where('id_kelas', $request->id_kelas_asal)
                ->where('id_thn_ajaran', $request->id_thn_ajaran_asal)
                ->get();
    
            if ($siswa->isEmpty()) {
                Alert::error('Gagal', 'Tidak ada siswa yang ditemukan');
                return response()->json(['message' => 'Tidak ada siswa yang ditemukan'], 404);
            }
    
            foreach ($siswa as $s) {
                // add tagihan
                $id_thn_ajaran_siswa = $request->id_thn_ajaran_tujuan;
                $tarifs = Tarif::where('id_kelas', $s->id_kelas)->whereHas('jenisPembayaran', function($query) use ($id_thn_ajaran_siswa){
                    return $query->where('id_thn_ajaran', $id_thn_ajaran_siswa);
                })->with('jenisPembayaran')->get();

                foreach ($tarifs as $k => $tarif) {
                    $totBayar = 0;
                    if ($tarif->jenisPembayaran->tipe_bayar == 'Bulanan') {
                        $totBayar = $tarif->tarif * 6;
                    } else {
                        $totBayar = $tarif->tarif;
                    }

                    $transaksi = Pembayaran::create([
                        'id_kelas' => $request->id_kelas_tujuan,
                        'id_tarif' => $tarif->id_tarif,
                        'id_siswa' => $s->id_siswa,
                        'id_jenis_pembayaran' => $tarif->jenisPembayaran->id_jenis_pembayaran,
                        'id_thn_ajaran' => $tarif->jenisPembayaran->id_thn_ajaran,
                        'invoice' => 'INV-' . time(),
                        'total_bayar' => $totBayar,
                        'status' => 'Belum Lunas',
                    ]);

                    if (!$transaksi) {
                        Alert::error('Error', 'Data transaksi gagal ditambahkan untuk siswa: ' . $siswa->nama);
                        break;
                        return redirect()->back()->with('error', 'Gagal membuat tagihan siswa');
                    }
                }

                $s->id_kelas = $request->id_kelas_tujuan;
                $s->id_thn_ajaran = $request->id_thn_ajaran_tujuan;
                $s->save();
            }
    
            return response()->json(['success' => 'Kenaikan kelas berhasil'], 200);
        }

    public function getSiswaByKelasAndTahunAjaran(Request $request)
    {
        $id_kelas = $request->query('kelas');
        $id_thn_ajaran = $request->query('tahunAjaran');

        $siswa = Siswa::where('id_kelas', $id_kelas)
                    ->where('id_thn_ajaran', $id_thn_ajaran)
                    ->with(['kelas', 'tahunAjaran','transaksi'])
                    ->get();
        
        $siswa = $siswa->map(function ($siswa) {
            $siswa->cek_transaksi = $siswa->transaksi->filter(function ($transaksi) use ($siswa) {
                return $transaksi->id_kelas == $siswa->id_kelas && $transaksi->id_thn_ajaran == $siswa->id_thn_ajaran;
            })->values()->all();

            return $siswa;
        });
        return response()->json(['siswa' => $siswa]);
    }


    public function checkUnpaid(Request $request)
    {
        $id_kelas_asal = $request->query('kelas');
        $id_thn_ajaran_asal = $request->query('tahunAjaran');

        $unpaidExists = false;
        $siswa = Siswa::where('id_kelas', $id_kelas_asal)->where('id_thn_ajaran', $id_thn_ajaran_asal)->with('transaksi', function($query) use ($id_kelas_asal, $id_thn_ajaran_asal){
            return $query->where('id_kelas', $id_kelas_asal)->where('id_thn_ajaran', $id_thn_ajaran_asal);
        })->get();
        // Pembayaran::where('id_kelas', $id_kelas_asal)
        //     ->where('id_thn_ajaran', $id_thn_ajaran_asal)
        //     ->where('status', '!=', 'Lunas')
        //     ->exists();

        foreach ($siswa as $key => $value) {
            if (count($value->transaksi) == 0) {
                $unpaidExists = true;
                break;
            }

            foreach ($value->transaksi as $k => $transaksi) {
                if ($transaksi->status == 'Belum Lunas') {
                    $unpaidExists = true;
                    break;
                }
            }
        }

        return response()->json(['unpaidExists' => $unpaidExists]);
    }
}
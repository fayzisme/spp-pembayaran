<?php

namespace App\Http\Controllers;

use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Tarif;
use App\Models\Pembayaran;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $search = $request->input('search');
        $siswa = Siswa::all();

        // return view('pages.data.tahun-ajaran.index', compact('tahunAjaran'));

        // $siswa = Siswa::query()
        //     ->where('nama', 'like', "%{$search}%")
        //     ->paginate(50);
    
        return view('pages.data.siswa.index', compact('siswa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::all();
        $tahunAjaran = TahunAjaran::all();
        $selected_thn_ajaran = TahunAjaran::latest()->first();

        return view('pages.data.siswa.create', compact('kelas', 'tahunAjaran', 'selected_thn_ajaran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username2' => 'required|string|max:255|unique:users,username',
            // 'email' => 'required|email:rfc,dns|unique:users,email',
            'email' => 'required|email|unique:users,email',
            'password2' => 'required|string|min:8',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_thn_ajaran' => 'required|exists:thn_ajaran,id_thn_ajaran',
            'nis' => 'required|max:4|unique:siswas,nis',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|string|max:255',
            'no_hp' => 'required|max_digits:15',
            'alamat' => 'required|string|max:255',
            'nama_wali' => 'required|string|max:255',
            // 'status' => 'required|in:Aktif,Non-aktif',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $user = User::create([
            'username' => $request->username2,
            'email' => $request->email,
            'password' => Hash::make($request->password2),
            'id_role' => 3,
        ]);

        if (!$user) {
            Alert::error('Gagal', 'Data user gagal ditambahkan');
            return redirect()->route('siswa.create');
        }

        $siswa = Siswa::create([
            'id_user' => $user->id_user,
            'id_kelas' => $request->id_kelas,
            'id_thn_ajaran' => $request->id_thn_ajaran,
            'nis' => $request->nis,
            'nama' => $request->nama,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'nama_wali' => $request->nama_wali,
            // 'status' => $request->status,
        ]);

        if(!$siswa) {
            $user->delete();
            Alert::error('Gagal', 'Data siswa gagal ditambahkan');
            return redirect()->route('siswa.create');
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $request->nis . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $user->image = $imageName;
            $user->save();
            
        }//untuk tambah siswa dapat tarif
        $id_thn_ajaran_siswa = $siswa->id_thn_ajaran;
        $tarifs = Tarif::where('id_kelas', $siswa->id_kelas)
        ->whereHas('jenisPembayaran', function($query) use ($id_thn_ajaran_siswa){
            return $query->where('id_thn_ajaran', $id_thn_ajaran_siswa);
        })
        ->with('jenisPembayaran')->get();

        foreach ($tarifs as $k => $tarif) {
            $totBayar = 0;
            if ($tarif->jenisPembayaran->tipe_bayar == 'Bulanan') {
    
                $currentMonth = date('n');
                $jml_bln = 6;

                if ($siswa->tahunAjaran->semester == 'Ganjil') { 
                    if ($currentMonth > 7) {
                        $jml_bln = 12 - intval($currentMonth);
                    }                   
                } 
                if ($siswa->tahunAjaran->semester == 'Genap') { 
                    if ($currentMonth > 1 && $currentMonth < 7) {
                        $jml_bln = 6 - intval($currentMonth);
                    }                   
                }

                if ($jml_bln < 1) {
                    $jml_bln = 1;
                }

                $totBayar = $tarif->tarif * $jml_bln;
            } else {
                $totBayar = $tarif->tarif;
            }

            $transaksi = Pembayaran::create([
                'id_kelas' => $siswa->id_kelas,
                'id_tarif' => $tarif->id_tarif,
                'id_siswa' => $siswa->id_siswa,
                'id_jenis_pembayaran' => $tarif->jenisPembayaran->id_jenis_pembayaran,
                'id_thn_ajaran' => $tarif->jenisPembayaran->id_thn_ajaran,
                'invoice' => 'INV-' . time(),
                'total_bayar' => $totBayar,
                'status' => 'Belum Lunas',
            ]);

            if (!$transaksi) {
                Alert::error('Error', 'Data transaksi gagal ditambahkan untuk siswa: ' . $siswa->nama);
                return redirect()->back()->with('error', 'Gagal membuat tagihan siswa');
            }
        }


        $sendMail = Mail::send('mail.user-credential', ['username' => $user->username, 'password' => $request->password2], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Informasi Akun Siswa');
        });

        if(!$sendMail) {
            Alert::error('Gagal', 'Gagal mengirim email');
            return redirect()->back()->with('error', 'Gagal mengirim email');
        }

        Alert::success('Berhasil', 'Berhasil menambahkan data siswa');
        return redirect()->route('siswa.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $siswa = Siswa::find($id);
        if(!$siswa) {
            Alert::error('Gagal', 'Data siswa tidak ditemukan');
            return redirect()->back();
        }

        return view('pages.data.siswa.show', compact('siswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $siswa = Siswa::find($id);
        $kelas = Kelas::all();
        $tahunAjaran = TahunAjaran::all();

        if(!$siswa) {
            Alert::error('Gagal', 'Data siswa tidak ditemukan');
            return redirect()->back();
        }

        return view('pages.data.siswa.edit', compact('siswa', 'kelas', 'tahunAjaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_thn_ajaran' => 'required|exists:thn_ajaran,id_thn_ajaran',
            'nis' => 'required|max:5|unique:siswas,nis,' .  $id . ',id_siswa', // perhatikan id_siswa
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|string|max:255',
            'no_hp' => 'required|max_digits:15',
            'alamat' => 'required|string|max:255',
            'nama_wali' => 'required|string|max:255',
            // 'status' => 'required|in:Aktif,Non-aktif',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $siswa = Siswa::find($id);

        if(!$siswa) {
            Alert::error('Gagal', 'Data siswa tidak ditemukan');
            return redirect()->back();
        }

        $user = User::find($siswa->id_user);

        if(!$user) {
            Alert::error('Gagal', 'Data user tidak ditemukan');
            return redirect()->back();
        }

        $user->username = $request->username2;
        $user->email = $request->email;

        if (isset($request->password) && $request->password != '') {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $request->nis . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $user->image = $imageName;
        }

        $user->save();

        $is_updated_transaksi = false;
        $id_kelas_before = $siswa->id_kelas;
        $id_thn_ajaran_before = $siswa->id_thn_ajaran;
        if ($id_kelas_before != $request->id_kelas || $id_thn_ajaran_before != $request->id_thn_ajaran) {
            $is_updated_transaksi = true;
        }

        $siswa->id_kelas = $request->id_kelas;
        $siswa->id_thn_ajaran = $request->id_thn_ajaran;
        $siswa->nis = $request->nis;
        $siswa->nama = $request->nama;
        $siswa->tempat_lahir = $request->tempat_lahir;
        $siswa->tgl_lahir = $request->tgl_lahir;
        $siswa->jenis_kelamin = $request->jenis_kelamin;
        $siswa->agama = $request->agama;
        $siswa->no_hp = $request->no_hp;
        $siswa->alamat = $request->alamat;
        $siswa->nama_wali = $request->nama_wali;
        // $siswa->status = $request->status;
        $siswa->save();

        if ($is_updated_transaksi) {
            //untuk update siswa tarif
            $id_thn_ajaran_siswa = $siswa->id_thn_ajaran;
            $tarifs = Tarif::where('id_kelas', $siswa->id_kelas)->whereHas('jenisPembayaran', function($query) use ($id_thn_ajaran_siswa){
                return $query->where('id_thn_ajaran', $id_thn_ajaran_siswa);
            })->with('jenisPembayaran')->get();
    
            foreach ($tarifs as $k => $tarif) {
                $totBayar = 0;
                if ($tarif->jenisPembayaran->tipe_bayar == 'Bulanan') {
                    $totBayar = $tarif->tarif * 6;
                } else {
                    $totBayar = $tarif->tarif;
                }

                $pembayarans = Pembayaran::whereHas('jenisPembayaran', function($query) use($tarif){
                    return $query->where('tipe_bayar', $tarif->jenisPembayaran->tipe_bayar);
                })->where(['id_kelas' => $id_kelas_before, 'id_siswa' => $siswa->id_siswa, 'id_thn_ajaran' => $id_thn_ajaran_before])->withSum('detailPembayaran', 'jumlah_transaksi')->with('jenisPembayaran')->get();

                if (count($pembayarans)) {
                    //update data
                    foreach ($pembayarans as $key => $pembayaran) {
                        //hitung akumulasi pembayaran di kelas/tahun ajaran sebelumnya
                        $kekurangan = floatval($totBayar) - floatval($pembayaran->detail_pembayaran_sum_jumlah_transaksi);
                        if (round($kekurangan, 2) <= 0) {
                            $kekurangan = 0;
                        }
                        $pembayaran->id_kelas = $siswa->id_kelas;
                        $pembayaran->id_tarif = $tarif->id_tarif;
                        $pembayaran->id_jenis_pembayaran = $tarif->jenisPembayaran->id_jenis_pembayaran;
                        $pembayaran->id_thn_ajaran = $tarif->jenisPembayaran->id_thn_ajaran;
                        $pembayaran->total_bayar = $totBayar;
    
                        if (round($kekurangan, 2) > 0) {
                            $pembayaran->status = 'Belum Lunas';
                        }
                        else {
                            $pembayaran->status = 'Lunas';
                        }
    
                        $pembayaran->save();
                    }
                }
                else {
                    //create data
                    $transaksi = Pembayaran::create([
                        'id_kelas' => $siswa->id_kelas,
                        'id_tarif' => $tarif->id_tarif,
                        'id_siswa' => $siswa->id_siswa,
                        'id_jenis_pembayaran' => $tarif->jenisPembayaran->id_jenis_pembayaran,
                        'id_thn_ajaran' => $tarif->jenisPembayaran->id_thn_ajaran,
                        'invoice' => 'INV-' . time(),
                        'total_bayar' => $totBayar,
                        'status' => 'Belum Lunas',
                    ]);   
                }
                

            }     
        }

        Alert::success('Berhasil', 'Berhasil mengubah data siswa');
        return redirect()->route('siswa.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::find($id);

        if(!$siswa) {
            Alert::error('Gagal', 'Data siswa tidak ditemukan');
            return redirect()->back();
        }

        $user = User::find($siswa->id_user);

        if(!$user) {
            Alert::error('Gagal', 'Data user tidak ditemukan');
            return redirect()->back();
        }

        $siswa->delete();
        $user->delete();

        Alert::success('Berhasil', 'Berhasil menghapus data siswa');
        return redirect()->route('siswa.index');
    }

    public function export()
    {
        return Excel::download(new SiswaExport, 'siswa.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new SiswaImport, $request->file('file'));

        Alert::success('Berhasil', 'Berhasil mengimport data siswa');
        return redirect()->route('siswa.index');
    }

    public function exportPdf()
{
    try {
        $data = DB::table('siswas')
            ->join('thn_ajaran', 'siswas.id_thn_ajaran', '=', 'thn_ajaran.id_thn_ajaran')
            ->join('kelas', 'siswas.id_kelas', '=', 'kelas.id_kelas')
            ->select('thn_ajaran.thn_ajaran', 'siswas.nis', 'siswas.nama', 'siswas.tempat_lahir', 'siswas.tgl_lahir', 'siswas.jenis_kelamin', 'siswas.agama', 'siswas.no_hp', 'siswas.alamat', 'siswas.nama_wali', 'kelas.tingkat', 'kelas.nama_kelas')
            ->orderBy('thn_ajaran.thn_ajaran', 'asc') // Menambahkan klausa orderBy
            ->get();

        $pdf = PDF::loadView('eksporpdf.siswa', compact('data'))->setPaper('a4', 'landscape');

        return $pdf->stream('Data Siswa.pdf');
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.profile.index');
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
    public function update(Request $request)
    {
        if (Auth::user()->id_role == 3) {
            // Siswa
            // dd($request->all());
            $request->validate([
                'nama' => 'required|string',
                'alamat' => 'required|string',
                'no_hp' => 'required|string|max_digits:15',
                'tempat_lahir' => 'required|string',
                'tgl_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:L,P',
                'agama' => 'required|string',
                'nama_wali' => 'required|string',
            ]);

            $user = User::find(Auth::user()->id_user);
            $user->siswa->nama = $request->nama;
            $user->siswa->alamat = $request->alamat;
            $user->siswa->no_hp = $request->no_hp;
            $user->siswa->tempat_lahir = $request->tempat_lahir;
            $user->siswa->tgl_lahir = $request->tgl_lahir;
            $user->siswa->jenis_kelamin = $request->jenis_kelamin;
            $user->siswa->agama = $request->agama;
            $user->siswa->nama_wali = $request->nama_wali;
            $user->save();
            $user->siswa->save();

            if (!$user || !$user->siswa) {
                Alert::error('Gagal', 'Profil gagal diubah');
                return redirect()->back();
            }

            Alert::success('Berhasil', 'Profil berhasil diubah');
            return redirect()->back();
        } else {
            // Petugas
            $request->validate([
                'username' => 'required|unique:users,username,' . Auth::user()->id_user . ',id_user',
                'email' => 'required|string|email|unique:users,email,' . Auth::user()->id_user . ',id_user',
                'nama' => 'required|string',
                'alamat' => 'required|string',
                'no_hp' => 'required|string|max_digits:15',
            ]);

            $user = User::find(Auth::user()->id_user);
            $user->username = $request->username;
            $user->email = $request->email;
            $user->petugas->nama = $request->nama;
            $user->petugas->alamat = $request->alamat;
            $user->petugas->no_hp = $request->no_hp;
            $user->save();
            $user->petugas->save();

            if (!$user || !$user->petugas) {
                Alert::error('Gagal', 'Profil gagal diubah');
                return redirect()->back();
            }

            Alert::success('Berhasil', 'Profil berhasil diubah');
            return redirect()->back();
        }
    }

    // public function updateImage(Request $request)
    // {
    //     $request->validate([
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);        

    //     $user = User::find(Auth::user()->id_user);

    //     // Hapus foto lama jika ada
    // if ($user->image) {
    //     Storage::delete('public/images/' . $user->image);

    //     if ($request->file('image')) {
    //         $image = $request->file('image');
    //         $imageName = time() . '.' . $image->getClientOriginalExtension();
    //         $image->move(public_path('images'), $imageName);
    //         $user->image = $imageName;
    //         $user->save();
    //     }

    //     if (!$user) {
    //         Alert::error('Gagal', 'Foto gagal diubah');
    //         return redirect()->back();
    //     }

    //     Alert::success('Berhasil', 'Foto berhasil diubah');
    //     return redirect()->back();
    // }
    // }

    // public function updateImage(Request $request)
    // {
    //     $request->validate([
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     $user = User::find(Auth::user()->id_user);

    //     if ($user) {
    //         // Hapus foto lama jika ada
    //         $oldImage = $user->image;
    //         if ($request->hasFile('image')) {
    //             try {
    //                 // Proses penggantian foto dengan yang baru
    //                 $image = $request->file('image');
    //                 $imageName = time() . '.' . $image->getClientOriginalExtension();
    //                 $image->storeAs('public/images', $imageName);

    //                 // Simpan nama foto baru ke dalam database
    //                 $user->image = $imageName;
    //                 $user->save();

    //                 // Hapus foto lama setelah foto baru berhasil disimpan
    //                 if ($oldImage) {
    //                     Storage::delete('public/images/' . $oldImage);
    //                 }

    //                 Alert::success('Berhasil', 'Foto berhasil diubah');
    //                 return redirect()->back();
    //             } catch (\Exception $e) {
    //                 // Jika ada error, kembalikan pesan error
    //                 Alert::error('Gagal', 'Terjadi kesalahan saat mengunggah foto: ' . $e->getMessage());
    //                 return redirect()->back();
    //             }
    //         } else {
    //             Alert::error('Gagal', 'Foto gagal diunggah');
    //             return redirect()->back();
    //         }
    //     } else {
    //         Alert::error('Gagal', 'Pengguna tidak ditemukan');
    //         return redirect()->back();
    //     }
    // }
    public function updateImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::find(Auth::user()->id_user);

        if ($request->file('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $user->image = $imageName;
            $user->save();
        }

        if (!$user) {
            Alert::error('Gagal', 'Foto gagal diubah');
            return redirect()->back();
        }

        Alert::success('Berhasil', 'Foto berhasil diubah');
        return redirect()->back();
    }


    public function changePassword(Request $request)
    {
        $request->validate([
            'currentPassword' => 'required|min:8',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::find(Auth::user()->id_user);

        if (!password_verify($request->currentPassword, $user->password)) {
            Alert::error('Gagal', 'Password lama salah');
            return redirect()->back();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        if (!$user) {
            Alert::error('Gagal', 'Password gagal diubah');
            return redirect()->back();
        }

        Alert::success('Berhasil', 'Password berhasil diubah');
        return redirect()->back();
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirmPassword' => 'required|min:8',
        ]);

        $user = User::find(Auth::user()->id_user);

        if (!password_verify($request->confirmPassword, $user->password)) {
            Alert::error('Gagal', 'Password salah');
            return redirect()->back();
        }

        $user->delete();

        if (!$user) {
            Alert::error('Gagal', 'Akun gagal dihapus');
            return redirect()->back();
        }

        Alert::success('Berhasil', 'Akun berhasil dihapus');
        return redirect()->route('login');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $search = $request->input('search');

        $petugas = Petugas::all();

        // $petugas = Petugas::query()
        //     ->where('nama', 'like', "%{$search}%")
        //     ->get();
        // ->paginate(10);

        return view('pages.data.petugas.index', compact('petugas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.data.petugas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // 'email' => 'required|email:rfc,dns|unique:users,email',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'id_role' => 'required|exists:roles,id_role',
            'nama' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required|max_digits:15',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $user = User::create([
            'username' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => $request->id_role,
        ]);

        $petugas = Petugas::create([
            'id_user' => $user->id_user,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        if (!$petugas) {
            $user->delete();
            Alert::error('Gagal', 'Data petugas gagal ditambahkan');
            return redirect()->route('petugas.create');
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $user->image = $imageName;
            $user->save();
        }

        $sendMail = Mail::send('mail.admin-credential', ['username' => $user->username, 'password' => $request->password], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Informasi Akun Petugas');
        });

        if (!$sendMail) {
            Alert::error('Gagal', 'Gagal mengirim email');
            return redirect()->back()->with('error', 'Gagal mengirim email');
        }

        Alert::success('Berhasil', 'Data petugas berhasil ditambahkan');
        return redirect()->route('petugas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $petugas = Petugas::find($id);

        if(!$petugas) {
            Alert::error('Gagal', 'Data petugas tidak ditemukan');
            return redirect()->back();
        }

        return view('pages.data.petugas.show', compact('petugas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $petugas = Petugas::findOrFail($id);
        $roles = Role::where('id_role', '!=', 3)->get(); // 3 is the id_role for Siswa

        return view('pages.data.petugas.edit', compact('petugas', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_role' => 'required|exists:roles,id_role',
            'nama' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required|max_digits:15',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        // $petugas = Petugas::findOrFail($id);
        // $user = User::findOrFail($petugas->id_user);
        $petugas = Petugas::where('id_petugas', $id)->first();
        $user = User::where('id_user', $petugas->id_user)->first();

        if ($user->email !== $request->email) {
            $request->validate([
                'email' => 'unique:users,email',
            ]);

            $user->email = $request->email;
        }

        if ($user->username !== $request->nama) {
            $request->validate([
                'nama' => 'unique:users,username',
            ]);

            $user->username = $request->nama;
        }

        $user->id_role = $request->id_role;
        $user->save();

        $petugas->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        if (!$petugas || !$user) {
            Alert::error('Gagal', 'Data petugas gagal diubah');
            return redirect()->route('petugas.edit', $id);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $user->image = $imageName;
            $user->save();
        }

        Alert::success('Berhasil', 'Data petugas berhasil diubah');
        return redirect()->route('petugas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $petugas = Petugas::findOrFail($id);
        $user = User::findOrFail($petugas->id_user);

        if (!$petugas || !$user) {
            Alert::error('Gagal', 'Data petugas tidak ditemukan');
            return redirect()->route('petugas.index');
        }

        $petugas->delete();
        $user->delete();

        Alert::success('Berhasil', 'Data petugas berhasil dihapus');
        return redirect()->route('petugas.index');
    }
}

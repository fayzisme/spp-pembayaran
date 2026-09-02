<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use RealRashid\SweetAlert\Facades\Alert;

class SiswaImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // dd($collection);
        foreach ($collection as $key => $row) {
            // dd($row);
                $user = User::create([
                    'id_role' => 3, // 3 = 'Siswa
                    'username' => $row['nis'],
                    'email' => $row['email'],
                    'password' => Hash::make(env('SISWA_DEFAULT_PASSWORD')),
                ]);

                if (!Kelas::where('tingkat', $row['tingkat'])->where('nama_kelas', $row['kelas'])->exists()) {
                    Kelas::create([
                        'tingkat' => $row['tingkat'],
                        'nama_kelas' => $row['kelas'],
                    ]);
                }

                if (!TahunAjaran::where('thn_ajaran', $row['tahun_ajaran'])->where('semester', $row['semester'])->exists()) {
                    TahunAjaran::create([
                        'thn_ajaran' => $row['tahun_ajaran'],
                        'semester' => $row['semester'],
                    ]);
                }

                $siswa = Siswa::create([
                    'id_user' => $user->id_user,
                    'id_kelas' => Kelas::where('tingkat', $row['tingkat'])->where('nama_kelas', $row['kelas'])->first()->id_kelas,
                    'id_thn_ajaran' => TahunAjaran::where('thn_ajaran', $row['tahun_ajaran'])->where('semester', $row['semester'])->first()->id_thn_ajaran,
                    'nis' => $row['nis'],
                    'nama' => $row['nama'],
                    'tempat_lahir' => $row['tempat_lahir'],
                    'tgl_lahir' => date('Y-m-d', strtotime($row['tanggal_lahir'])),
                    'jenis_kelamin' => $row['jenis_kelamin'],
                    'agama' => $row['agama'],
                    'no_hp' => $row['no_hp'],
                    'alamat' => $row['alamat'],
                    'nama_wali' => $row['nama_wali'],
                    // 'status' => 'Aktif',
                ]);

                if ($user && $siswa) {
                    // send to mail username(nis) and password
                    Mail::send('mail.user-credential', ['username' => $user->username, 'password' => env('SISWA_DEFAULT_PASSWORD')], function ($message) use ($user) {
                        $message->to($user->email)->subject('Informasi Akun Siswa');
                    });
                    Alert::success('Berhasil', 'Data siswa berhasil diimport');
                }
        }
    }
}

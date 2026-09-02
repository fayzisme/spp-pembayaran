<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create petugas tata usaha
        User::create([
            'username' => 'Admin',
            'email' => 'nisaannisa1324@gmail.com',
            'password' => bcrypt('12345678'),
            'id_role' => 1,
        ]);

        // create kepala sekolah
        User::create([
            'username' => 'Kepala Sekolah',
            'email' => 'kepalasekolah@gmail.com',
            'password' => bcrypt('12345678'),
            'id_role' => 2,
        ]);

        // create siswa
        User::create([
            'username' => 'Siswa',
            'email' => 'siswa2@gmail.com',
            'password' => bcrypt('12345678'),
            'id_role' => 3,
        ]);
    }
}

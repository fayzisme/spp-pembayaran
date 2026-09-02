<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $siswa = [];

        for ($i = 0; $i < 5; $i++) {
            $nis = $faker->unique()->numberBetween(10000, 99999);

            $user = User::factory()->create([
                'id_role' => 3,
                'username' => $nis,
                'password' => bcrypt('abcd1234')
            ]);

            $siswa[] = [
                'id_user' => $user->id_user,
                'id_kelas' => Kelas::all()->random()->id_kelas,
                'id_thn_ajaran' => TahunAjaran::all()->random()->id_thn_ajaran,
                'nis' => $nis,
                'nama' => $faker->name,
                'tempat_lahir' => $faker->city,
                'tgl_lahir' => $faker->date,
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'agama' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu']),
                'no_hp' => substr($faker->phoneNumber, 0, 15),
                'alamat' => $faker->address,
                'nama_wali' => $faker->name,
                // 'status' => $faker->randomElement(['Aktif', 'Non-aktif']),
            ];
        }

        foreach ($siswa as $item) {
            Siswa::create($item);
        }
        
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // tahun_ajaran dan semester
        $tahunAjaran = [
          
            ['thn_ajaran' => '2023/2024', 'semester' => 'Ganjil'],
            ['thn_ajaran' => '2023/2024', 'semester' => 'Genap'],
            ['thn_ajaran' => '2024/2025', 'semester' => 'Genap'],
            ['thn_ajaran' => '2025/2026', 'semester' => 'Genap'],
        ];

        foreach ($tahunAjaran as $tahunAjaran) {
            \App\Models\TahunAjaran::create($tahunAjaran);
        }
    }
}

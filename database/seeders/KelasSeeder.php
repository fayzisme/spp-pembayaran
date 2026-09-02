<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // tingkat (VII, VIII, IX) dan nama_kelas (A, B, C, D)
        $kelas = [
            ['tingkat' => 'VII', 'nama_kelas' => 'A'],
            ['tingkat' => 'VII', 'nama_kelas' => 'B'],
            ['tingkat' => 'VII', 'nama_kelas' => 'C'],
            ['tingkat' => 'VII', 'nama_kelas' => 'D'],
            ['tingkat' => 'VIII', 'nama_kelas' => 'A'],
            ['tingkat' => 'VIII', 'nama_kelas' => 'B'],
            ['tingkat' => 'VIII', 'nama_kelas' => 'C'],
            ['tingkat' => 'VIII', 'nama_kelas' => 'D'],
            ['tingkat' => 'IX', 'nama_kelas' => 'A'],
            ['tingkat' => 'IX', 'nama_kelas' => 'B'],
            ['tingkat' => 'IX', 'nama_kelas' => 'C'],
            ['tingkat' => 'IX', 'nama_kelas' => 'D'],
        ];

        foreach ($kelas as $kelas) {
            \App\Models\Kelas::create($kelas);
        }
    }
}

<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('siswas')
            ->join('thn_ajaran', 'siswas.id_thn_ajaran', '=', 'thn_ajaran.id_thn_ajaran')
            ->join('kelas', 'siswas.id_kelas', '=', 'kelas.id_kelas')
            ->select(
                DB::raw('CONCAT(thn_ajaran.thn_ajaran, "/", thn_ajaran.semester) as thn_ajaran_semester'),
                'siswas.nis', 
                'siswas.nama', 
                'siswas.tempat_lahir', 
                'siswas.tgl_lahir', 
                'siswas.jenis_kelamin', 
                'siswas.agama', 
                'siswas.no_hp', 
                'siswas.alamat', 
                'siswas.nama_wali', 
                // 'siswas.status', 
                DB::raw('CONCAT(kelas.tingkat, " ", kelas.nama_kelas) as kelas')
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            'thn_ajaran/semester',
            'nis',
            'nama',
            'tempat_lahir',
            'tgl_lahir',
            'jenis_kelamin',
            'agama',
            'no_hp',
            'alamat',
            'nama_wali',
            // 'status',
            'kelas'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D3D3D3']]],
        ];
    }
}
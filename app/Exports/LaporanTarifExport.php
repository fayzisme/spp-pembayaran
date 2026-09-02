<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\JenisPembayaran; // Sesuaikan dengan model yang digunakan

class LaporanTarifExport implements FromCollection, WithHeadings, WithStyles
{
    protected $jenisPembayaran;

    public function __construct($jenisPembayaran)
    {
        $this->jenisPembayaran = $jenisPembayaran;
    }

    public function collection()
    {
        return $this->jenisPembayaran->flatMap(function ($jenis) {
            return $jenis->tarif->map(function ($tarif) use ($jenis) {
                return [
                    'Nama Pembayaran' => $jenis->nama_pembayaran,
                    'Tahun Ajaran' => $jenis->tahunAjaran->thn_ajaran,
                    'Semester' => $jenis->tahunAjaran->semester,
                    'Kelas' => $tarif->kelas->tingkat . ' ' . $tarif->kelas->nama_kelas,
                    'Tarif Pembayaran' => $tarif->tarif ? 'Rp. ' . number_format($tarif->tarif, 0, ',', '.') : '',
                ];
            });
        })->whenEmpty(function ($collection) {
            return $this->getEmptyRow();
        });
    }

    public function headings(): array
    {
        return [
            'Nama Pembayaran',
            'Tahun Ajaran',
            'Semester',
            'Kelas',
            'Tarif Pembayaran',
        ];
    }

    private function getEmptyRow()
    {
        return new Collection([
            [
                'Nama Pembayaran' => 'Data tidak ditemukan',
                'Tahun Ajaran' => '',
                'Semester' => '',
                'Kelas' => '',
                'Tarif Pembayaran' => '',
            ]
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Gaya untuk header tabel
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => '000000']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'D3D3D3'],
                ],
            ],
        ];
    }
}
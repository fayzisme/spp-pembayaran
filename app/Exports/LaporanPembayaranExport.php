<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPembayaranExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private $details;
    private $index = 0; // Menambahkan properti untuk melacak nomor urut
    private $isEmpty = false; // Properti untuk melacak apakah data kosong

    public function __construct($details)
    {
        $this->details = $details;

        // Periksa apakah data kosong
        if ($this->details->isEmpty()) {
            $this->isEmpty = true;
        }
    }

    public function collection()
    {
            // Jika data kosong, kembalikan koleksi dengan satu item untuk menampilkan pesan
        if ($this->isEmpty) {
            return collect([[
                'No' => '',
                'Nama Siswa' => 'Data tidak ditemukan',
                'Nama Pembayaran' => '',
                'Kelas' => '',
                'Tahun Ajaran' => '',
                'Semester' => '',
                'Bulan' => '',
                'Total Pembayaran Yang Baru Dibayarkan' => '',
                'Metode Pembayaran' => '',
                'Nama Petugas' => '',
                'Status' => '',
                'Tanggal Pembayaran' => '',
                'Sisa Tanggungan' => '',
            ]]);
        }

        return $this->details->filter(function ($detail) {
            // Hanya kembalikan detail yang valid
            return $detail->siswa && $detail->jenisPembayaran && $detail->tarif && $detail->tarif->kelas && $detail->tahunAjaran;
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Nama Pembayaran',
            'Kelas',
            'Tahun Ajaran',
            'Semester',
            'Bulan',
            'Total Pembayaran Yang Baru Dibayarkan',
            'Metode Pembayaran',
            'Nama Petugas',
            'Status',
            'Tanggal Pembayaran',
            'Sisa Tanggungan',
        ];
    }

    public function map($detail): array
    {
        $this->index++; // Increment nomor urut

        // Cek apakah semua relasi yang dibutuhkan tersedia
        if (!$detail->siswa || !$detail->jenisPembayaran || !$detail->tarif || !$detail->tarif->kelas || !$detail->tahunAjaran) {
            // Jangan menambahkan baris untuk data yang tidak lengkap
            return [];
        }

        $totalTarif = $detail->tarif->tarif;
        $jumlahTransaksi = $detail->jumlah_transaksi;

        if ($detail->jenisPembayaran->nama_pembayaran === 'SPP') {
            $initialTanggungan = 6 * $totalTarif;
        } else {
            $initialTanggungan = $totalTarif;
        }

        static $sisaTanggungan = [];
        $pembayaranKey = $detail->siswa->id_siswa . '-' . $detail->jenisPembayaran->id_jenis_pembayaran;
        
        if (!isset($sisaTanggungan[$pembayaranKey])) {
            $sisaTanggungan[$pembayaranKey] = $initialTanggungan;
        }

        $sisaTanggungan[$pembayaranKey] -= $jumlahTransaksi;

        return [
            $this->index, // Menambahkan nomor urut
            $detail->siswa->nama,
            $detail->jenisPembayaran->nama_pembayaran,
            $detail->tarif->kelas->tingkat . ' ' . $detail->tarif->kelas->nama_kelas,
            $detail->tahunAjaran->thn_ajaran,
            $detail->tahunAjaran->semester,
            $detail->bulan,
            $detail->jumlah_transaksi,
            $detail->metode_transaksi,
            $detail->metode_transaksi == 'Tunai' ? $detail->petugas->nama : '--',
            $detail->status_transaksi,
            $detail->created_at,
            $sisaTanggungan[$pembayaranKey],
        ];
    }


    public function styles(Worksheet $sheet)
    {
        return [
            // Gaya untuk header tabel
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'D3D3D3']
                ]
            ],
        ];
    }
}
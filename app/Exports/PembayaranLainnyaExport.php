<?php
namespace App\Exports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PembayaranLainnyaExport implements FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        return Pembayaran::with([
            'tahunAjaran',
            'kelas',
            'jenisPembayaran' => function ($query) {
                $query->where('tipe_bayar', 'Bebas');
            },
            'detailPembayaran'
        ])
            ->where('id_transaksi', $this->id)
            ->get()
            ->map(function ($item) {
                return $item->detailPembayaran->map(function ($detail) use ($item) {
                    return [
                        'NIS' => $item->siswa->nis,
                        'Nama Siswa' => $item->siswa->nama,
                        'Kelas' => $item->kelas->tingkat . ' ' . $item->kelas->nama_kelas,
                        'Tahun Ajaran' => $item->tahunAjaran->thn_ajaran,
                        'Semester' => $item->tahunAjaran->semester,
                        'Jenis Pembayaran' => $item->jenisPembayaran->nama_pembayaran,
                        'Dibayar' => $detail->jumlah_transaksi ? 'Rp. ' . number_format($detail->jumlah_transaksi, 0, ',', '.') : 'Rp. 0',
                        'Status Bayar' => $detail->status_transaksi,
                    ];
                });
            })->collapse();
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama Siswa',
            'Kelas',
            'Tahun Ajaran',
            'Semester',
            'Jenis Pembayaran',
            'Dibayar',
            'Status Bayar',
        ];
    }
}

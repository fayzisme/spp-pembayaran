<?php

namespace App\Exports;

use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TunggakanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $id_thn_ajaran;
    protected $id_kelas;
    protected $rowNumber = 0; // Initialize row number

    public function __construct($id_thn_ajaran, $id_kelas)
    {
        $this->id_thn_ajaran = $id_thn_ajaran;
        $this->id_kelas = $id_kelas;
    }

    public function collection()
    {
        $query = Siswa::with(['kelas', 'transaksi', 'transaksi.tahunAjaran', 'transaksi.jenisPembayaran', 'transaksi.detailPembayaran', 'transaksi.tarif']);

        if ($this->id_thn_ajaran) {
            $query->whereHas('transaksi.tahunAjaran', function ($q) {
                $q->where('id_thn_ajaran', $this->id_thn_ajaran);
            });
        }

        if ($this->id_kelas) {
            $query->where('id_kelas', $this->id_kelas);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Tagihan pada Kelas',
            'Tahun',
            'Nama Pembayaran / Tipe Pembayaran',
            'Total Yang Harus Dibayar',
            'Sisa Tanggungan',
            'Bulan SPP Yang Lunas',
            'Status Pembayaran',
        ];
    }

    public function map($item): array
    {
        $this->rowNumber++; // Increment row number for each student

        $transaksiGrouped = $item->transaksi->groupBy(function ($transaksi) {
            return $transaksi->tahunAjaran->thn_ajaran . ' ' . $transaksi->tahunAjaran->semester;
        });

        if ($transaksiGrouped->isEmpty()) {
            return [
                $this->rowNumber,
                $item->nama,
                $item->kelas->tingkat . ' ' . $item->kelas->nama_kelas,
                '-',
                '-',
                'Rp 0',
                'Rp 0',
                '-',
                'Belum Ada Tagihan',
            ];
        }

        $result = [];
        foreach ($transaksiGrouped as $tahunSemester => $transaksiList) {
            $pembayaranDetails = $this->getPembayaranDetails($transaksiList);

            foreach ($pembayaranDetails as $namaPembayaran => $details) {
                $statusPembayaran = $details['sisa'] > 0 ? 'Belum Lunas' : 'Lunas';

                $result[] = [
                    $this->rowNumber,
                    $item->nama,
                    $item->kelas->tingkat . ' ' . $item->kelas->nama_kelas,
                    $tahunSemester,
                    $namaPembayaran . ' / ' . ucfirst($details['tipe']),
                    'Rp ' . number_format($details['total'], 0, ',', '.'),
                    $details['sisa'] > 0 ? 'Rp ' . number_format($details['sisa'], 0, ',', '.') : 'Rp 0',
                    strtolower($details['tipe']) === 'bulanan' ? implode(', ', $details['bulan']) : '-',
                    $statusPembayaran,
                ];
            }
        }

        return $result;
    }

    private function getPembayaranDetails($transaksiList)
    {
        $pembayaranDetails = [];

        foreach ($transaksiList as $transaksi) {
            $namaPembayaran = $transaksi->jenisPembayaran->nama_pembayaran;
            $tipePembayaran = $transaksi->jenisPembayaran->tipe_bayar;
            $tarif = $transaksi->tarif->tarif ?? null;

            if ($tipePembayaran === 'bebas') {
                // Untuk pembayaran bebas, sisa tanggungan dihitung berdasarkan transaksi yang ada
                $totalTarifSemester = 0; // Misalnya untuk tipe bebas, nilai total diatur berdasarkan logika spesifik
                $totalDibayar = $transaksi->detailPembayaran->sum('jumlah_transaksi');
                $sisa = $totalTarifSemester - $totalDibayar;
            } else {
                // Untuk tipe pembayaran lain, gunakan logika yang sudah ada
                $totalTarifSemester = strtolower($tipePembayaran) === 'bulanan' ? ($tarif ?? 0) * 6 : $tarif ?? 0;
                $totalDibayar = $transaksi->detailPembayaran->sum('jumlah_transaksi');
                $sisa = $totalTarifSemester - $totalDibayar;
            }

            if (!isset($pembayaranDetails[$namaPembayaran])) {
                $pembayaranDetails[$namaPembayaran] = [
                    'tipe' => $tipePembayaran,
                    'sisa' => 0,
                    'total' => 0,
                    'bulan' => []
                ];
            }

            $pembayaranDetails[$namaPembayaran]['sisa'] += $sisa;
            $pembayaranDetails[$namaPembayaran]['total'] += $totalTarifSemester;

            if (strtolower($tipePembayaran) === 'bulanan') {
                foreach ($transaksi->detailPembayaran as $detail) {
                    $bulan = $detail->bulan;
                    if (!in_array($bulan, $pembayaranDetails[$namaPembayaran]['bulan'])) {
                        $pembayaranDetails[$namaPembayaran]['bulan'][] = $bulan;
                    }
                }
            }
        }

        return $pembayaranDetails;
    }
}

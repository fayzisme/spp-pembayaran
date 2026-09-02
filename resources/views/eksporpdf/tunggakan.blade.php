<!DOCTYPE html>
<html>
<head>
    <title>Data Tagihan Pembayaran Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f2f2f2;
        }
        .center {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2 class="center">Data Tagihan Pembayaran Siswa</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Tagihan pada Kelas</th>
                <th>Tahun</th>
                <th>Nama Pembayaran / Tipe Pembayaran</th>
                <th>Total Yang Harus Dibayar</th>
                <th>Sisa Tanggungan</th>
                <th>Bulan SPP Yang Lunas</th>
                <th>Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @forelse ($siswa as $item)
                @php
                    $transaksiGrouped = $item->transaksi->groupBy(function ($transaksi) {
                        return $transaksi->tahunAjaran->thn_ajaran . ' ' . $transaksi->tahunAjaran->semester;
                    });
                @endphp
                @if($transaksiGrouped->isEmpty())
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->kelas->tingkat . ' ' . $item->kelas->nama_kelas }}</td>
                        <td>-</td>
                        <td>-</td>
                        <td>Rp 0</td>
                        <td>Rp 0</td>
                        <td>-</td>
                        <td>Belum Ada Tagihan</td>
                    </tr>
                @else
                    @foreach ($transaksiGrouped as $tahunSemester => $transaksiList)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $transaksiList->first()->kelas->tingkat . ' ' . $transaksiList->first()->kelas->nama_kelas }}</td>
                            <td>{{ $tahunSemester }}</td>
                            @php
                                $totalTunggakan = 0;
                                $totalHarusDibayar = 0;
                                $pembayaranDetails = [];
                                $kelasTarifBelumDiberikan = true;

                                foreach ($transaksiList as $transaksi) {
                                    $namaPembayaran = $transaksi->jenisPembayaran->nama_pembayaran;
                                    $tipePembayaran = $transaksi->jenisPembayaran->tipe_bayar;
                                    $tarif = $transaksi->tarif->tarif ?? null;

                                    if (!is_null($tarif)) {
                                        $kelasTarifBelumDiberikan = false;
                                        if (strtolower($tipePembayaran) === 'bulanan') {
                                            $totalTarifSemester = $tarif * 6;
                                        } else {
                                            $totalTarifSemester = $tarif;
                                        }

                                        $totalDibayar = $transaksi->detailPembayaran->sum('jumlah_transaksi');
                                        $sisa = $totalTarifSemester - $totalDibayar;

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
                                }
                            @endphp
                            <td>
                                @if ($kelasTarifBelumDiberikan)
                                    <p>-</p>
                                @else
                                    @foreach ($pembayaranDetails as $namaPembayaran => $details)
                                        <p>{{ $namaPembayaran }} / {{ ucfirst($details['tipe']) }}</p>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if ($kelasTarifBelumDiberikan)
                                    <p>Rp 0</p>
                                @else
                                    @foreach ($pembayaranDetails as $namaPembayaran => $details)
                                        <p>{{ 'Rp ' . number_format($details['total'], 0, ',', '.') }}</p>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if ($kelasTarifBelumDiberikan)
                                    <p>Rp 0</p>
                                @else
                                    @foreach ($pembayaranDetails as $namaPembayaran => $details)
                                        <p>{{ $details['sisa'] > 0 ? 'Rp ' . number_format($details['sisa'], 0, ',', '.') : 'Rp 0' }}</p>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if ($kelasTarifBelumDiberikan)
                                    <p>-</p>
                                @else
                                    @foreach ($pembayaranDetails as $namaPembayaran => $details)
                                        @if (strtolower($details['tipe']) === 'bulanan')
                                            <p>{{ implode(', ', $details['bulan']) }}</p>
                                        @else
                                            <p>-</p>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusPembayaran = true;
                                    if ($kelasTarifBelumDiberikan) {
                                        $statusPembayaran = false;
                                    } else {
                                        foreach ($pembayaranDetails as $details) {
                                            if ($details['sisa'] > 0) {
                                                $statusPembayaran = false;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                <p>{{ $statusPembayaran ? 'Lunas' : 'Belum Lunas' }}</p>
                            </td>
                        </tr>
                    @endforeach
                @endif
            @empty
                <tr>
                    <td colspan="9" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

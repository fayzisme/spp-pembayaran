<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid rgb(175, 172, 172);
            padding: 5px;
        }
        th {
            background-color: #f2f2f2;
        }
        @page {
            size: A4 landscape;
        }
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 80px;
            margin-right: 30px;
        }
        .header .text {
            flex-grow: 1;
            text-align: center;
        }
        .header .text h2,
        .header .text h3,
        .header .text h6 {
            line-height: 1;
            margin: 3px 0;
        }
        .header, .subheader {
    margin: 0;
    padding: 0;
    line-height: 1.2; /* Adjust the line height to control spacing between lines */
}

.header {
    margin-bottom: 5px; /* Adjust the margin as needed */
}

.subheader {
    margin-top: 0;
    margin-bottom: 5px; /* Adjust the margin as needed */
}

        .header .text h6 {
            font-weight: normal;
        }
        .divider {
            border-top: 1px solid black;
            margin: 10px 0;
        }
        .signature-table {
            width: 100%;
            margin-top: 50px;
            text-align: center;
            border: none;
        }
        .signature-table td {
            padding: 10px;
            border: none;
        }
        .signature-title {
            margin-bottom: 80px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="text">
            <h2 class="header">SMP PEMDA 2 Kesugihan</h2>
            <h5 class="subheader">Jl. Belimbing No. 17, Kesugihan, Cilacap, 53274</h5>
            <h5 class="subheader">Telp. 0282-5070563 & Email: smppemda2ksh@gmail.com</h5>

            <div class="divider"></div>
        </div>
    </div>
    <h2 style="text-align: center;">Laporan Pembayaran</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Nama Pembayaran</th>
                <th>Kelas</th>
                <th>Tahun Ajaran</th>
                <th>Semester</th>
                <th>Bulan</th>
                <th>Total Pembayaran</th>
                <th>Metode Pembayaran</th>
                <th>Nama Petugas</th>
                <th>Status</th>
                <th>Tanggal Pembayaran</th>
                <th>Sisa Tanggungan</th>
            </tr>
        </thead>
        <tbody>
            @if ($details->isEmpty())
                <tr>
                    <td colspan="13" style="text-align: center;">Data tidak ditemukan</td>
                </tr>
            @else
                @php
                    $sisaTanggungan = [];
                    $i = 1;
                @endphp
                @foreach ($details as $detail)
                    @if ($detail->siswa && $detail->jenisPembayaran && $detail->tarif && $detail->tarif->kelas && $detail->tahunAjaran)
                        @php
                            $pembayaranKey = $detail->siswa->id_siswa . '-' . $detail->jenisPembayaran->id_jenis_pembayaran;
                            $tarif = $detail->tarif->tarif;

                            if (!isset($sisaTanggungan[$pembayaranKey])) {
                                $sisaTanggungan[$pembayaranKey] = $detail->jenisPembayaran->tipe_bayar == 'Bulanan' ? $tarif * 6 : $tarif;
                            }

                            $sisaTanggungan[$pembayaranKey] -= $detail->jumlah_transaksi;
                        @endphp
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $detail->siswa->nama }}</td>
                            <td>{{ $detail->jenisPembayaran->nama_pembayaran }}</td>
                            <td>{{ $detail->tarif->kelas->tingkat . ' ' . $detail->tarif->kelas->nama_kelas }}</td>
                            <td>{{ $detail->tahunAjaran->thn_ajaran }}</td>
                            <td>{{ $detail->tahunAjaran->semester }}</td>
                            <td>{{ $detail->bulan }}</td>
                            <td>Rp. {{ number_format($detail->jumlah_transaksi, 0, ',', '.') }}</td>
                            <td>{{ $detail->metode_transaksi }}</td>
                            <td>{{ $detail->metode_transaksi == 'Tunai' ? $detail->petugas->nama : '--' }}</td>
                            <td>{{ $detail->status_transaksi }}</td>
                            <td>{{ \Carbon\Carbon::parse($detail->tgl_transaksi)->format('d-m-Y') }}</td>
                            <td>Rp. {{ number_format($sisaTanggungan[$pembayaranKey], 0, ',', '.') }}</td>
                        </tr>
                        @php $i++ @endphp
                    @endif
                @endforeach
            @endif
        </tbody>
    </table>
    <table class="signature-table">
        <tr>
            <td>
                <p class="signature-title">Kepala Sekolah</p>
                <p>(Sri Kusnun, S.Pd)</p>
            </td>
            <td>
                <p class="signature-title">Petugas Tata Usaha</p>
                <p>(Suwarti)</p>
            </td>
        </tr>
    </table>
</body>
</html>

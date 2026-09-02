<!DOCTYPE html>
<html>

<head>
    <title>Slip Pembayaran</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 10px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse; /* Ensures no gaps between cells */
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            padding: 10px; /* Ensures padding is consistent */
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.total td {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        .invoice-box table tr.information td {
            vertical-align: top;
        }

        .information table {
            width: 100%;
        }

        .information td {
            padding: 4px;
            line-height: 1.4;
        }

        .information tr {
            margin: 0;
        }

        .information table {
            width: 100%;
            border-collapse: collapse;
        }

        .information td {
            padding: 4px;
        }

        .information td:first-child {
            width: 150px;
        }

        /* Styling for the right-aligned section */
        .right-aligned {
            text-align: right;
            padding-right: 10px; /* Adjust this value to control distance from right edge */
        }

        .right-aligned span {
            display: block; /* Ensures each item is on a new line */
            margin-bottom: 5px; /* Adds some space between lines */
        }

        .right-aligned div {
            display: inline-block;
            margin-left: 10px; /* Space between elements */
        }

        /* New style for left-aligned total */
        .total-left {
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <h2>Slip Bayar</h2>
                                <h5>SMP PEMDA 2 Kesugihan</h5>
                            </td>
                            <td class="right-aligned">
                                <div>Invoice: #{{ $detailPembayaran->transaksi->invoice . '-' . $detailPembayaran->created_at->format('YmdHis') }}</div>
                                <div>Tanggal: {{ $detailPembayaran->created_at->format('d-m-Y') }}</div>
                                {{-- <div>Due: {{ $detailPembayaran->created_at->addDays(1)->format('d-m-Y') }}</div> --}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td><span class="label">Nama Siswa:</span></td>
                            <td>{{ $detailPembayaran->siswa->nama }}</td>
                        </tr>
                        <tr>
                            <td><span class="label">Kelas:</span></td>
                            <td>{{ $detailPembayaran->siswa->kelas->tingkat . ' ' . $detailPembayaran->siswa->kelas->nama_kelas }}</td>
                        </tr>
                        <tr>
                            <td><span class="label">Nama Pembayaran:</span></td>
                            <td>{{ $detailPembayaran->transaksi->jenisPembayaran->nama_pembayaran }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>Nama Pembayaran</td>
                @if ($detailPembayaran->transaksi->jenisPembayaran->tipe_bayar == 'Bulanan')
                    <td>Bulan</td>
                    <td>Status Transaksi</td>
                @endif
                <td>Jumlah</td>
                <td>Metode Transaksi</td>
                <td>Nama Petugas</td>
            </tr>
            <tr class="item">
                <td>{{ $detailPembayaran->transaksi->jenisPembayaran->nama_pembayaran . ' ' . $detailPembayaran->transaksi->jenisPembayaran->tahunAjaran->thn_ajaran . ' - ' . $detailPembayaran->transaksi->jenisPembayaran->tahunAjaran->semester }}</td>
                @if ($detailPembayaran->transaksi->jenisPembayaran->tipe_bayar == 'Bulanan')
                    <td>{{ $detailPembayaran->bulan }}</td>
                    <td>{{ $detailPembayaran->status_transaksi }}</td>
                @endif
                <td>Rp. {{ number_format($detailPembayaran->jumlah_transaksi, 0, ',', '.') }}</td>
                <td>{{ $detailPembayaran->metode_transaksi }}</td>
                <td>
                    @if ($detailPembayaran->metode_transaksi == 'Tunai')
                        {{ $detailPembayaran->petugas->nama ?? '--' }}
                    @else
                        --
                    @endif
                </td>
            </tr>
            <tr class="total">
                <td colspan="2" class="total-left">Total: Rp. {{ number_format($detailPembayaran->jumlah_transaksi, 0, ',', '.') }}</td>
                <td colspan="4"></td>
            </tr>
        </table>
    </div>
</body>

</html>

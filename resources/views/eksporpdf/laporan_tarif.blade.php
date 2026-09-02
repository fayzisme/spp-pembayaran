<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Tarif Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; }

        .table-laporan {
            width: 100%; border-collapse: collapse; margin-top: 20px;
        }
        .th-table-laporan {
            background-color: #e9ecf0; color: rgb(29, 28, 28);
        }
        .table-laporan th, .table-laporan td {
            border: 1px solid rgb(49, 49, 49);
        }
        .table-laporan th, .table-laporan td {
            padding: 8px; text-align: left;
        }

        .table-borderless tr td {
            border: none;
        }
        .bagian-foto {
            text-align: right;
        }
        .imgLogo {
            width: 35%;
        }
        .bagian-text {
            text-align: left !important;
            vertical-align: top;
        }
        .text-isi {
            text-align: center;
        }
        .header .text-isi h2,
        .header .text-isi h3,
        .header .text-isi h6 {
            line-height: 1.2;
            margin: 5px 0;
        }
        .header .text h6 {
            font-weight: normal;
        }
        .divider {
            border-top: 1px solid black;
            margin: 10px 0;
        }
        .report-title {
            text-align: center;
            margin-top: 20px;
        }
        .signature-table {
            width: 100%;
            margin-top: 10px;
        }
        .signature-table td {
            width: 50%; text-align: center; vertical-align: top; padding-top: 20px;
        }
        .signature-title {
            margin-bottom: 60px;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="table-borderless" width="100%">
            <tr>
                <td width="30%" class="bagian-foto"><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/logo.jpg'))) }}" class="imgLogo" alt="Logo"></td>
                <td class="bagian-text">
                    <div class="text-isi">
                        {{-- <h3>Yayasan Pendidikan Pembudi Darma</h3> --}}
                        <h2>SMP PEMDA 2 Kesugihan</h2>
                        <h6>Jl. Belimbing No. 17, Kesugihan, Cilacap, 53274</h6>
                        <h6>Telp. 0282-5070563 & Email: smppemda2ksh@gmail.com</h6>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="divider"></div>
    <h3 class="report-title">Laporan Tarif Pembayaran</h3>
    <table class="table-laporan">
        <thead>
            <tr class="th-table-laporan">
                <th>No</th>
                <th>Nama Pembayaran</th>
                <th>Tahun Ajaran</th>
                <th>Semester</th>
                <th>Kelas</th>
                <th>Tarif Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @forelse ($jenisPembayaran as $item)
                @foreach ($item->tarif as $tarif)
                    <tr>
                        <td>{{ $counter }}</td>
                        <td>{{ $item->nama_pembayaran }}</td>
                        <td>{{ $item->tahunAjaran->thn_ajaran }}</td>
                        <td>{{ $item->tahunAjaran->semester }}</td>
                        <td>{{ $tarif->kelas->tingkat . ' ' . $tarif->kelas->nama_kelas }}</td>
                        <td>{{ $tarif->tarif ? 'Rp. ' . number_format($tarif->tarif, 0, ',', '.') : '' }}</td>
                    </tr>
                    @php $counter++; @endphp
                @endforeach
            @empty
                <tr>
                    <td colspan="6" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                <p class="signature-title">Kepala Sekolah</p>
                <p><u>Sri Kusnun, S.Pd</u></p>
            </td>
            <td>
                <p class="signature-title">Petugas Tata Usaha</p>
                <p><u>Suwarti</u></p>
            </td>
        </tr>
    </table>
</body>
</html>

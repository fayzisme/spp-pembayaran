<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Tarif Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #e9ecf0; color: rgb(29, 28, 28); }
        table, th, td { border: 1px solid rgb(49, 49, 49); }
        th, td { padding: 8px; text-align: left; }
        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 120px;
            margin-right: 20px;
        }
        .header .text {
            text-align: center;
            flex-grow: 1;
        }
        .header .text h2,
        .header .text h3,
        .header .text h6 {
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
    </style>
</head>
<body>
    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/logo.png'))) }}" alt="Logo">
        <div class="text">
            <h3>Yayasan Pendidikan Pembudi Darma</h3>
            <h2>SMP PEMDA 2 Kesugihan</h2>
            <h6>Jl. Belimbing No. 17, Kesugihan, Cilacap, 53274</h6>
            <h6>Telp. 0282-5070563 & Email: smppemda2ksh@gmail.com</h6>
            <div class="divider"></div>
        </div>
    </div>
    <h3 class="report-title">Laporan Tarif Pembayaran</h3>
    <table>
        <thead>
            <tr>
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
</body>
</html>
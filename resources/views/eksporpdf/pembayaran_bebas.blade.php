<!DOCTYPE html>
<html>
<head>
    <title>Slip Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #6d6c6c;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #ffffff;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f1efef;
        }
        .signature-container {
            margin-top: 20px;
            width: 100%;
            text-align: right;
        }
        .signature {
            display: inline-block;
            text-align: center;
            width: 250px;
        }
        .signature div {
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        .signature p {
            margin: 0;
            padding: 0;
        }
        .signature .name {
            margin-top: 50px; /* Adjust the value to add space */
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .date {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="text-align: center; flex-grow: 1;">Riwayat Pembayaran</h2>
        {{-- <div class="date">{{ $tanggalHariIni }}</div> --}}
    </div>

    <body>
        @foreach ($pembayaran as $item)
            <div class="student-info">
                <p>Nama: {{ $item['Nama Siswa'] ?? 'N/A' }}</p>
                <p>NIS: {{ $item['NIS'] ?? 'N/A' }}</p>
                <p>Kelas: {{ $item['Kelas'] ?? 'N/A' }}</p>
            </div>
            @break
        @endforeach
    </body>
    
    <table class="table-striped">
        <thead>
            <tr>
                <th>Tahun Ajaran</th>
                <th>Semester</th>
                <th>Jenis Pembayaran</th>
                <th>Dibayar</th>
                <th>Status Bayar</th>
                <th>Metode Transaksi</th>
                <th>Nama Petugas</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembayaran as $item)
                <tr>
                    <td>{{ $item['Tahun Ajaran'] }}</td>
                    <td>{{ $item['Semester'] }}</td>
                    <td>{{ $item['Jenis Pembayaran'] }}</td>
                    <td>{{ $item['Dibayar'] }}</td>
                    <td>{{ $item['Status Bayar'] }}</td>
                    <td>{{ $item['Metode Transaksi'] }}</td>
                    <td>{{ $item['Metode Transaksi'] == 'Tunai' ? $item['Nama Petugas'] : '--' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['Tanggal'])->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-container">
        <div class="signature">
            <p>Ketua Komite</p>
            <p>SMP PEMDA 2 Kesugihan</p>
            {{-- <div>___________________</div> --}}
            <p class="name"><u>Soeyaga M.Zain</u></p>
        </div>
    </div>
</body>
</html>

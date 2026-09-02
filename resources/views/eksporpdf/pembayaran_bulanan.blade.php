<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran SPP</title>
    <style>
        body {
            position: relative;
            margin: 0;
            padding: 20px;
            font-size: 12px; /* Perkecil ukuran huruf */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #918f8f;
            padding: 8px;
            text-align: left;
            font-size: 13px; /* Perkecil ukuran huruf */
        }
        thead {
            background-color: #e0e0e0; /* Lebih abu-abu */
        }
        tbody tr:nth-child(odd) {
            background-color: #f9f9f9; /* Tabel striped */
        }
        tbody tr:nth-child(even) {
            background-color: #ffffff; /* Tabel striped */
        }
        .signature {
            margin-top: 20px; /* Sesuaikan margin atas */
            text-align: center;
        }
        .signature-content {
            width: 100px;
            margin-left: auto;
            margin-right: 0; /* Posisi ke kanan */
        }
        .signature-line {
            margin-top: 60px;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Riwayat Pembayaran SPP</h2>
    
    @foreach ($pembayaran as $item)
        <div class="student-info">
            <p>Nama: {{ $item['Nama Siswa'] ?? 'N/A' }}</p>
            <p>NIS: {{ $item['NIS'] ?? 'N/A' }}</p>
            <p>Kelas: {{ $item['Kelas'] ?? 'N/A' }}</p>
        </div>
        @break
    @endforeach
    
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
                    <td>
                        {{ \Carbon\Carbon::parse($item['Tanggal'])->format('d-m-Y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <div class="signature-content">
            <p>Ketua Komite</p>
            <p>SMP PEMDA 2 Kesugihan</p>
            <div class="signature-line">Soeyaga M.Zain</div>
        </div>
    </div>
</body>
</html>

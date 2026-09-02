<!DOCTYPE html>
<html>
<head>
    <title>Data Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 100px;
            height: auto;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
        }
        .subtitle {
            font-size: 15px;
        }
        .container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid rgb(133, 131, 131);
        }
        th {
            background-color: #ebebeb; /* Warna abu-abu untuk heading tabel */
            font-size: 10px; /* Memperkecil ukuran huruf heading */
        }
        th, td {
            padding: 8px;
            text-align: left;
            word-wrap: break-word;
            font-size: 14px; /* Memperkecil ukuran huruf isi tabel */
        }
        .nama-wali {
            max-width: 200px;
            white-space: normal;
            word-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        @media print {
            @page {
                size: A4 landscape; /* Mengatur ukuran kertas menjadi A4 dengan orientasi landscape */
                margin: 1cm;
            }
            body {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .container {
                width: auto;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/logo.jpg'))) }}" class="logo" alt="Logo Sekolah">
        <br>
        <div class="title">Data Peserta Didik SMP Pemda 2 Kesugihan</div>
        <div class="subtitle">Jl. Belimbing No. 17, Menganti, Kec. Kesugihan, Kab. Cilacap, 53274</div>
        <div class="subtitle">Telp. 0282-5070563 & Email: smppemda2ksh@gmail.com</div>
    </div>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Tahun Ajaran</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Agama</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th>Nama Wali</th>
                    {{-- <th>Status</th> --}}
                    <th>Kelas</th> <!-- Menggabungkan Tingkat dan Nama Kelas -->
                </tr>
            </thead>
            <tbody>
                @foreach($data as $siswa)
                <tr>
                    <td>{{ $siswa->thn_ajaran }}</td>
                    <td>{{ $siswa->nis }}</td>
                    <td>{{ $siswa->nama }}</td>
                    <td>{{ $siswa->tempat_lahir }}</td>
                    <td>{{ $siswa->tgl_lahir }}</td>
                    <td>{{ $siswa->jenis_kelamin }}</td>
                    <td>{{ $siswa->agama }}</td>
                    <td>{{ $siswa->no_hp }}</td>
                    <td>{{ $siswa->alamat }}</td>
                    <td>{{ $siswa->nama_wali }}</td>
                    {{-- <td>{{ $siswa->status }}</td> --}}
                    <td>{{ $siswa->tingkat }}{{ $siswa->nama_kelas }}</td> <!-- Menggabungkan Tingkat dan Nama Kelas -->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>

@extends('layouts.app')

@section('title', 'Tunggakan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Tagihan Siswa</h3>
                <div class="card-tools">
                    <form action="{{ route('tunggakan.index') }}" class="row" method="GET">
                        <div class="form-group col">
                            <select name="id_thn_ajaran" id="id_thn_ajaran" class="form-control @error('id_thn_ajaran') is-invalid @enderror">
                                <option value="">Pilih Tahun Ajaran</option>
                                @foreach ($tahunAjaran as $item)
                                    <option value="{{ $item->id_thn_ajaran }}" {{ request('id_thn_ajaran') == $item->id_thn_ajaran ? 'selected' : '' }}>
                                        {{ $item->thn_ajaran }} {{ $item->semester }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_thn_ajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col">
                            <select name="id_kelas" id="id_kelas" class="form-control @error('id_kelas') is-invalid @enderror">
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id_kelas }}" {{ request('id_kelas') == $item->id_kelas ? 'selected' : '' }}>
                                        {{ $item->tingkat }} {{ $item->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                            <a href="{{ route('tunggakan.index') }}" class="btn btn-danger"><i class="fas fa-sync"></i> Refresh</a>
                        </div>
                    </form>
                    <div class="mt-3">
                        <a href="{{ route('tunggakan.exportPdf', request()->query()) }}" class="btn btn-danger" target="_blank">
                            <i class="fas fa-file-pdf"></i> Ekspor PDF
                        </a>
                        <a href="{{ route('tunggakan.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Ekspor Excel
                        </a>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body overflow-auto">
                <table id="tagihanSiswa" class="table table-bordered table-striped">
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
                                            $totalHarusDibayar = 0;
                                            $totalDibayar = 0;
                                            $pembayaranDetails = [];
                                            foreach ($transaksiList as $transaksi) {
                                                $namaPembayaran = $transaksi->jenisPembayaran->nama_pembayaran;
                                                $tipePembayaran = $transaksi->jenisPembayaran->tipe_bayar;
                                                $totalBayar = $transaksi->total_bayar;
                                                $dibayar = $transaksi->detailPembayaran->sum('jumlah_transaksi');
                                                $sisa = $totalBayar - $dibayar;

                                                // Debug logika perhitungan
                                                // echo "<p>$namaPembayaran | Tipe: $tipePembayaran | Total Bayar: $totalBayar | Dibayar: $dibayar | Sisa: $sisa</p>";

                                                if (!isset($pembayaranDetails[$namaPembayaran])) {
                                                    $pembayaranDetails[$namaPembayaran] = [
                                                        'tipe' => $tipePembayaran,
                                                        'sisa' => 0,
                                                        'total' => 0,
                                                        'bulan' => []
                                                    ];
                                                }

                                                $pembayaranDetails[$namaPembayaran]['sisa'] += $sisa;
                                                $pembayaranDetails[$namaPembayaran]['total'] += $totalBayar;

                                                if (strtolower($tipePembayaran) === 'bulanan') {
                                                    foreach ($transaksi->detailPembayaran as $detail) {
                                                        $bulan = $detail->bulan;
                                                        if (!in_array($bulan, $pembayaranDetails[$namaPembayaran]['bulan'])) {
                                                            $pembayaranDetails[$namaPembayaran]['bulan'][] = $bulan;
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        <td>
                                            @foreach ($pembayaranDetails as $namaPembayaran => $details)
                                                <p>{{ $namaPembayaran }} / {{ ucfirst($details['tipe']) }}</p>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($pembayaranDetails as $namaPembayaran => $details)
                                                <p>{{ 'Rp ' . number_format($details['total'], 0, ',', '.') }}</p>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($pembayaranDetails as $namaPembayaran => $details)
                                                <p>{{ $details['sisa'] > 0 ? 'Rp ' . number_format($details['sisa'], 0, ',', '.') : 'Rp 0' }}</p>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($pembayaranDetails as $namaPembayaran => $details)
                                                @if (strtolower($details['tipe']) === 'bulanan')
                                                    <p>{{ implode(', ', $details['bulan']) }}</p>
                                                @else
                                                    <p>-</p>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @php
                                                $statusPembayaran = true;
                                                foreach ($pembayaranDetails as $details) {
                                                    if ($details['sisa'] > 0) {
                                                        $statusPembayaran = false;
                                                        break;
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
                                <td colspan="9" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js-dashboard')
<script>
    let table = new DataTable('.table');

</script>
@endsection
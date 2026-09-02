@extends('layouts.app')

@section('title', 'Laporan Pembayaran')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Laporan Pembayaran</h3>
                    <div class="card-options">
                        <!-- Form Filter -->
                        <form method="GET" action="{{ route('laporan-pembayaran.index') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <select class="form-control" id="id_thn_ajaran" name="id_thn_ajaran">
                                        <option selected disabled value>Pilih Tahun Ajaran</option>
                                        @foreach ($thn_ajaran as $item)
                                            <option value="{{ $item->id_thn_ajaran }}"
                                                {{ $item->id_thn_ajaran == request()->get('id_thn_ajaran') ? 'selected' : '' }}>
                                                {{ $item->thn_ajaran . ' - ' . $item->semester }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" id="id_kelas" name="id_kelas">
                                        <option selected disabled value>Pilih Kelas</option>
                                        @foreach ($kelas as $item)
                                            <option value="{{ $item->id_kelas }}"
                                                {{ $item->id_kelas == request()->get('id_kelas') ? 'selected' : '' }}>
                                                {{ $item->tingkat . ' ' . $item->nama_kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-primary btn-block" type="submit">Cari</button>
                                    <a href="{{ route('laporan-pembayaran.index') }}" class="btn btn-danger"><i class="fas fa-sync"></i> Refresh</a>
                                </div>
                            </div>
                        </form>

                        <div class="card-body">
                            <!-- Tombol Ekspor PDF -->
                            <div class="mt-3">
                                <a href="{{ route('laporan-pembayaran.export-pdf', ['id_thn_ajaran' => request()->get('id_thn_ajaran'), 'id_kelas' => request()->get('id_kelas')]) }}" class="btn btn-danger" target="_blank">
                                    <i class="fas fa-file-pdf"></i> Ekspor
                                </a>
                                <a href="{{ route('laporan-pembayaran.export-excel', ['id_thn_ajaran' => request()->get('id_thn_ajaran'), 'id_kelas' => request()->get('id_kelas')]) }}" class="btn btn-success">
                                    <i class="fas fa-file-excel"></i> Ekspor
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body overflow-auto">
                        <!-- Tabel Laporan Pembayaran -->
                        <table class="table table-bordered table-hover">
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
                                    <th>Status Siswa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                    $sisaTanggungan = []; // untuk menyimpan sisa tanggungan per siswa
                                @endphp
                                @foreach ($details as $detail)
                                    @php
                                        $siswa = $detail->siswa;
                                        $jenisPembayaran = $detail->jenisPembayaran;
                                        $tarif = $detail->tarif;
                                        $tahunAjaran = $detail->tahunAjaran;
                                        $petugas = $detail->petugas;

                                        $pembayaranKey = $siswa->id_siswa ?? null . '-' . $jenisPembayaran->id_jenis_pembayaran ?? null;
                                        $tarifValue = $tarif->tarif ?? 0;

                                         // Periksa apakah siswa ada dan statusnya tidak aktif (lulus)
                                            if ($siswa && ($siswa->status == 0 || $siswa->status == 1) && $jenisPembayaran && $tarif && $tahunAjaran) {
                                                $pembayaranKey = $siswa->id_siswa . '-' . $jenisPembayaran->id_jenis_pembayaran;
                                                $tarifValue = $tarif->tarif ?? 0;
                                            }
                                        if ($pembayaranKey) {
                                            if (!isset($sisaTanggungan[$pembayaranKey])) {
                                                $sisaTanggungan[$pembayaranKey] = $jenisPembayaran->tipe_bayar == 'Bulanan' ? $tarifValue * 6 : $tarifValue;
                                            }

                                            $sisaTanggungan[$pembayaranKey] -= $detail->jumlah_transaksi;
                                        }
                                    @endphp
                                    @if ($siswa && $jenisPembayaran && $tarif && $tahunAjaran)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $siswa->nama }}</td>
                                            <td>{{ $jenisPembayaran->nama_pembayaran }}</td>
                                            <td>{{ $tarif->kelas->tingkat . ' ' . $tarif->kelas->nama_kelas }}</td>
                                            <td>{{ $tahunAjaran->thn_ajaran }}</td>
                                            <td>{{ $tahunAjaran->semester }}</td>
                                            <td>{{ $detail->bulan }}</td>
                                            <td>Rp. {{ number_format($detail->jumlah_transaksi, 0, ',', '.') }}</td>
                                            <td>{{ $detail->metode_transaksi }}</td>
                                            <td>{{ $detail->metode_transaksi == 'Tunai' ? $petugas->nama : '--' }}</td>
                                            <td>{{ $detail->status_transaksi }}</td>
                                            <td>{{ $detail->created_at }}</td>
                                            <td>Rp. {{ number_format($sisaTanggungan[$pembayaranKey], 0, ',', '.') }}</td>
                                            <td>{{ $siswa->status == 0 ? 'Aktif' : 'Lulus' }}</td>
                                        </tr>
                                        @php $i++ @endphp
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End Tabel Laporan Pembayaran -->
                    </div>
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

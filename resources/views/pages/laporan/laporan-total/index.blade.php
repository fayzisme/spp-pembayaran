@extends('layouts.app')

@section('title', 'Laporan Total')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Laporan Total</h4>
                </div>
                <div class="card-body">
                    <div class="card-body overflow-auto">
                        <!-- Tabel Laporan Pembayaran -->
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Semester</th>
                                    <th>Nama Pembayaran</th>
                                    <th>Biaya Tarif</th>
                                    <th>Nama Kelas</th>
                                    <th>Total Pembayaran * Siswa</th>
                                    <th>Yang Sudah Dibayarkan</th>
                                    <th>Sisa Tanggungan</th>
                                </tr>
                            </thead>
                            {{-- <tbody>
                                @foreach($laporantotal as $index => $data)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $data->thn_ajaran }}</td>
                                        <td>{{ $data->semester }}</td>
                                        <td>{{ $data->nama_pembayaran }}</td>
                                        <td>{{ number_format($data->tarif, 2, ',', '.') }}</td>
                                        <td>{{ $data->tingkat }} {{ $data->nama_kelas }}</td>
                                        <td>{{ number_format($data->total_bayar * $data->jumlah_siswa, 2, ',', '.') }}</td>
                                        <td>{{ number_format($data->sudah_dibayarkan, 2, ',', '.') }}</td>
                                        <td>{{ number_format($data->sisa_tanggungan, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody> --}}
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

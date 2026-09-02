@extends('layouts.app')

@section('title', 'Laporan Tarif')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Laporan Tarif Pembayaran</h3>
                    <div class="row">
                        <div class="col-md-3">
                            <select class="form-control" id="id_thn_ajaran" name="id_thn_ajaran">
                                <option selected disabled value>Pilih Tahun Ajaran</option>
                                @foreach ($thn_ajaran as $item)
                                    <option value="{{ $item->id_thn_ajaran }}">
                                        {{ $item->thn_ajaran . ' - ' . $item->semester }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <form id="filterForm" method="GET" action="{{ route('laporan-tarif.index') }}">
                                <input type="hidden" name="id_thn_ajaran" id="filterIdThnAjaran">
                                <button class="btn btn-primary btn-block" type="button" id="filterButton">Cari</button>
                                <a href="{{ route('laporan-tarif.index') }}" class="btn btn-danger"><i class="fas fa-sync"></i> Refresh</a>
                            </form>
                            
                        </div>
                        <div class="card-tools">
                            {{-- Dropdown siswa, excel button, refresh button --}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('laporan-tarif.exportPDF', request()->query()) }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Ekspor
                        </a>
                        <a href="{{ route('laporan-tarif.export-excel', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Ekspor
                        </a>
                    </div>
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <table id="laporanTarif" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pembayaran</th>
                                <th>Tahun Ajaran/Semester</th>
                                <th>Kelas</th>
                                <th>Tarif Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody id="laporanTarifBody">
                            @php
                                $no = 1;
                            @endphp
                            @forelse ($jenisPembayaran as $item)
                                @foreach ($item->tarif as $tarif)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $item->nama_pembayaran }}</td>
                                        <td>{{ $item->tahunAjaran->thn_ajaran }} {{ $item->tahunAjaran->semester }}</td>
                                        <td>{{ $tarif->kelas->tingkat . ' ' . $tarif->kelas->nama_kelas }}</td>
                                        <td>{{ $tarif->tarif ? 'Rp. ' . number_format($tarif->tarif, 0, ',', '.') : '' }}
                                        </td>
                                    </tr>
                                    @php
                                        $no++;
                                    @endphp
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Data tidak ditemukan</td>
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

        document.getElementById('filterButton').addEventListener('click', function() {
            const selectedYear = document.getElementById('id_thn_ajaran').value;
            if (selectedYear) {
                document.getElementById('filterIdThnAjaran').value = selectedYear;
                document.getElementById('filterForm').submit();
            }
        });
    </script>
@endsection

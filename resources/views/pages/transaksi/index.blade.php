@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
    <div class="row">
        @if (Auth::user()->id_role == 1)
        @php
            $id_siswa = request()->input('id_siswa');
            $id_thn_ajaran = request()->input('id_thn_ajaran');
            $id_kelas = request()->input('id_kelas');
        @endphp
            <div class="mb-3">
                <div class="card">
                    <div class="card-header ">
                        <h3>Cari Pembayaran</h3>
                    </div>
                    <div class="card-body overflow-auto">
                        <form action="{{ route('transaksi.index') }}" method="GET">
                            {{-- @csrf --}}
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="id_thn_ajaran">Tahun Ajaran</label>
                                    <select class="form-control" id="id_thn_ajaran" name="id_thn_ajaran">
                                        <option value="" selected hidden disabled>Pilih Tahun Ajaran</option>
                                        @foreach ($tahunAjaran as $item)
                                            <option value="{{ $item->id_thn_ajaran }}" {{ $id_thn_ajaran == $item->id_thn_ajaran ? 'selected' : '' }}>{{ $item->thn_ajaran . ' ' . $item->semester }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="id_kelas">Kelas</label>
                                    <select class="form-control" id="id_kelas" name="id_kelas">
                                        <option value="" selected hidden disabled>Pilih Kelas</option>
                                        @foreach ($kelas as $item)
                                            <option value="{{ $item->id_kelas }}" {{ $id_kelas == $item->id_kelas ? 'selected' : '' }}>
                                                {{ $item->tingkat . ' ' . $item->nama_kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="id_siswa">Nama Siswa</label>
                                    <select class="form-control" name="id_siswa" id="id_siswa">
                                        <option value="" selected hidden disabled>Pilih Siswa</option>
                                        @foreach ($siswas as $item)
                                            <option value="{{ $item->id_siswa }}" {{ $id_siswa == $item->id_siswa ? 'selected' : '' }}>{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @elseif(Auth::user()->id_role == 3)
        @php
            $id_thn_ajaran = request()->input('id_thn_ajaran');
        @endphp
            <div class="mb-3">
                <div class="card">
                    <div class="card-header ">
                        <h3>Cari Pembayaran</h3>
                    </div>
                    <div class="card-body overflow-auto">
                        <form action="{{ route('transaksi.index') }}" method="GET">
                            {{-- @csrf --}}
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="id_thn_ajaran">Tahun Ajaran</label>
                                    <select class="form-control" id="id_thn_ajaran" name="id_thn_ajaran">
                                        <option value="" selected hidden disabled>Pilih Tahun Ajaran</option>
                                        @foreach ($tahunAjaran as $item)
                                            <option value="{{ $item->id_thn_ajaran }}" {{ $id_thn_ajaran == $item->id_thn_ajaran ? 'selected' : '' }}>{{ $item->thn_ajaran . ' ' . $item->semester }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        @if(isset($informasi_siswa) && count($informasi_siswa) > 0)
            <div id="info" class="mb-3">
                <div class="card">
                    <div class="card-header ">
                        <h3>Pembayaran</h3>
                    </div>
                    <div class="card-body overflow-auto">
                        <div class="card shadow mb-4">
                            <div class="card-header bg-success py-3">
                                <h6 class=" mb-0 text-white">
                                    Informasi Siswa
                                </h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <tbody>
                                        @forelse ($informasi_siswa as $value)
                                            @foreach ($value as $key => $item)    
                                                <tr>
                                                    <td>{{ str_replace('_', ' ', ucfirst($key)) }}</td>
                                                    {{-- <td>{{ $key }}</td> --}}
                                                    <td>: {{ $item }}</td>
                                                </tr>
                                            @endforeach
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center">Data tidak ditemukan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card shadow mb-4">
                            <div class="card-header bg-warning py-3">
                                <h6 class=" mb-0 text-white">
                                    Tagihan Bulanan
                                </h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tahun Ajaran</th>
                                            <th>Semester</th>
                                            <th>Kelas</th>
                                            <th>Jenis Pembayaran</th>
                                            <th>Dibayar</th>
                                            <th>Status Bayar</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($transaksiBulanan as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->tahunAjaran->thn_ajaran }}</td>
                                                <td>{{ $item->tahunAjaran->semester }}</td>
                                                <td>{{ $item->kelas->tingkat . ' ' . $item->kelas->nama_kelas }}</td>
                                                <td>{{ $item->jenisPembayaran->nama_pembayaran }}</td>
                                                <td>
                                                    {{ $item->jumlah_bayar ? 'Rp. ' . number_format($item->jumlah_bayar, 0, ',', '.') : 'Rp. 0' }}
                                                </td>
                                                <td>
                                                    @if ($item->status == 'Lunas')
                                                        <span class="badge bg-success">Lunas</span>
                                                    @else
                                                        <span class="badge bg-danger">Belum Lunas</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ('Lunas' == $item->status)
                                                       
                                                        <a href="{{ route('transaksi.show', $item->id_transaksi) }}"
                                                            class="btn btn-primary">Detail</a>
                                                        {{-- <a href="{{ route('transaksi.exportMonthly', $item->id_transaksi) }}"
                                                             class="btn btn-success"><i class="fas fa-file-excel"></i></a> --}}
                                                        <a href="{{ route('transaksi.exportBulanan', $item->id_transaksi) }}" 
                                                            class="btn btn-danger" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                                    @else
                                                        <a href="{{ route('transaksi.show', $item->id_transaksi) }}"
                                                            class="btn btn-primary">Detail</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Data tidak ditemukan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card shadow mb-4">
                            <div class="card-header bg-info py-3">
                                <h6 class=" mb-0 text-white">
                                    Tagihan Lainnya
                                </h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tahun Ajaran</th>
                                            <th>Semester</th>
                                            <th>Kelas</th>
                                            <th>Jenis Pembayaran</th>
                                            <th>Dibayar</th>
                                            <th>Status Bayar</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($transaksiLainnya as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->tahunAjaran->thn_ajaran }}</td>
                                                <td>{{ $item->tahunAjaran->semester }}</td>
                                                <td>{{ $item->kelas->tingkat . ' ' . $item->kelas->nama_kelas }}</td>
                                                <td>{{ $item->jenisPembayaran->nama_pembayaran }}</td>
                                                <td>
                                                    {{ $item->jumlah_bayar ? 'Rp. ' . number_format($item->jumlah_bayar, 0, ',', '.') : 'Rp. 0' }}
                                                </td>
                                                <td>
                                                    @if ($item->status == 'Lunas')
                                                        <span class="badge bg-success">Lunas</span>
                                                    @else
                                                        <span class="badge bg-danger">Belum Lunas</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ('Lunas' == $item->status)
                                                    <a href="{{ route('transaksi.showLain', $item->id_transaksi) }}"
                                                        class="btn btn-primary">Detail</a>
                                                    {{-- <a href="{{ route('transaksi.export', $item->id_transaksi) }}"
                                                        class="btn btn-success"><i class="fas fa-file-excel"></i></a> --}}
                                                    <a href="{{ route('transaksi.exportPdf', $item->id_transaksi) }}"
                                                        class="btn btn-danger" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                                    
                                                @else
                                                        <a href="{{ route('transaksi.showLain', $item->id_transaksi) }}"
                                                            class="btn btn-primary">Detail</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Data tidak ditemukan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection

@section('js-dashboard')
    <script>
        $(document).ready(function(){
            console.log('ready'); 
            var data_siswa = {!! json_encode($siswas) !!}
            var siswa_ids = [];
            siswa_ids = data_siswa.map(el => el.id_siswa + '');

            var kelasId = null;
            
            
            @if(!isset($id_siswa) && !isset($id_kelas))
                $('#id_siswa').attr('disabled', 'disabled');
                filterSiswa(siswa_ids);
            @else
                $('#id_siswa').removeAttr('disabled');
                siswa_ids = [];
                kelasId = {!! json_encode($id_kelas) !!}

                siswa_ids = data_siswa.filter(val => val.id_kelas == kelasId).map(el => el.id_siswa + '');
                filterSiswa(siswa_ids);
            @endif
            
            
            
            $('#id_kelas').on('change', function() {
                siswa_ids = [];
                kelasId = $(this).val();
                $('#id_siswa').val('');

                if (!kelasId) {
                    $('#id_siswa').attr('disabled', 'disabled');
                }
                else{
                    $('#id_siswa').removeAttr('disabled');
                }

                siswa_ids = data_siswa.filter(val => val.id_kelas == kelasId).map(el => el.id_siswa + '');

                filterSiswa(siswa_ids);
            });

            function filterSiswa(siswa_ids) {
                $('#id_siswa option').each(function() {
                        var optionValue = $(this).val();
                        // Contoh: Menyembunyikan dan mendisable option dengan value '1'
                        if (!siswa_ids.includes(optionValue)) {
                            $(this).attr('hidden', true);
                            $(this).attr('disabled', 'disabled');
                        }
                        else{
                            $(this).removeAttr('hidden');
                            $(this).removeAttr('disabled');
                        }
                    });
                
            }


        })
    </script>
@endsection
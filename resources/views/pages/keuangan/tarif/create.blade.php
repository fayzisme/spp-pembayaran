@extends('layouts.app')

@section('title', 'Tambah Tarif')

@section('content')
    <div class="row">
        {{-- show data jenis transaksi --}}
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h1>Data Jenis Pembayaran</h1>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="nama_pembayaran">Nama Pembayaran</label>
                        <input type="text" name="nama_pembayaran" id="nama_pembayaran" class="form-control"
                            value="{{ $jenisPembayaran->nama_pembayaran }}" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="thn_ajaran">Tahun Ajaran</label>
                        <input type="text" name="thn_ajaran" id="thn_ajaran" class="form-control"
                            value="{{ $jenisPembayaran->tahunAjaran->thn_ajaran }}" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="thn_ajaran">Semester</label>
                        <input type="text" name="semester" id="semester" class="form-control"
                            value="{{ $jenisPembayaran->tahunAjaran->semester }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="tipe_bayar">Tipe Pembayaran</label>
                        <input type="text" name="tipe_bayar" id="tipe_bayar" class="form-control"
                            value="{{ $jenisPembayaran->tipe_bayar }}" disabled>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h1>Tambah Tarif</h1>
                </div>
                <div class="card-body">
                    <form id="tarifForm" action="{{ route('tarif.store', $jenisPembayaran->id_jenis_pembayaran) }}" method="post">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="kelas">Kelas</label>
                            <select name="id_kelas" id="kelas" class="form-control">
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelas as $item)
                                    <option value="{{ /* $item->id_kelas */ $item->id_kelas }}">{{ $item->tingkat . $item->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="tarif">Tarif</label>
                            <input type="text" name="tarif" id="tarif" class="form-control">
                        </div>
                        <div class="form-group-row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary">Tambah</button>
                                <a href="{{ route('jenis-transaksi.index') }}" class="btn btn-secondary ml-3">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
@endsection

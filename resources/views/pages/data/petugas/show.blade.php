@extends('layouts.app')

@section('title', 'Detail Petugas')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Data Petugas</h3>
                    <div class="card-options">
                        <a href="{{ route('petugas.index') }}" class="btn btn-info">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ $petugas->user->image ? asset('images/' . $petugas->user->image) : asset('assets/img/icons/user_default.jpg') }}"
                                alt="{{ $petugas->nama }}" class="img-fluid">
                        </div>
                        <div class="col-md-8">
                            <table class="table table-striped">
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $petugas->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $petugas->alamat }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor HP</th>
                                    <td>{{ $petugas->no_hp }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $petugas->user->email }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
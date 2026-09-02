@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Data Siswa</h3>
                    <div class="card-options">
                        <a href="{{ route('siswa.index') }}" class="btn btn-info">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ $siswa->user->image ? asset('images/' . $siswa->user->image) : asset('assets/img/icons/user_default.jpg') }}"
                                alt="{{ $siswa->nama }}" class="img-fluid">
                        </div>
                        <div class="col-md-8">
                            <table class="table table-striped">
                                <tr>
                                    <th>NIS</th>
                                    <td>{{ $siswa->nis }}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $siswa->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Kelas</th>
                                    <td>{{ $siswa->kelas->tingkat . $siswa->kelas->nama_kelas }}</td>
                                </tr>
                                {{-- <tr>
                                    <th>Tahun Ajaran/th>
                                    <td>{{ $siswa->thn_ajaran->thn_ajaran }}</td>
                                </tr> --}}
                                {{-- <tr>
                                    <th>Email</th>
                                    <td>{{ $siswa->user->email }}</td>
                                </tr> --}}
                                <tr>
                                    <th>Tempat Lahir</th>
                                    <td>{{ $siswa->tempat_lahir }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Lahir</th>
                                    <td>{{ $siswa->tgl_lahir }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>{{ $siswa->jenis_kelamin }}</td>
                                </tr>
                                <tr>
                                    <th>Agama</th>
                                    <td>{{ $siswa->agama }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $siswa->alamat }}</td>
                                </tr>
                                <tr>
                                    <th>No. HP</th>
                                    <td>{{ $siswa->no_hp }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Wali</th>
                                    <td>{{ $siswa->nama_wali }}</td>
                                </tr>
                                <tr>
                                    <th>Tahun Ajaran</th>
                                    <td>{{ $siswa->tahunAjaran->thn_ajaran . ' - ' . $siswa->tahunAjaran->semester }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $siswa->user->email }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
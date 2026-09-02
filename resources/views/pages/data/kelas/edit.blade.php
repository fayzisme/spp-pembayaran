@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title ">Edit Kelas</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('kelas.update', $kelas->id_kelas) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tingkat" class="form-label">Tingkat</label>
                                <select class="form-control" id="tingkat" name="tingkat" required>
                                    <option value="" disabled hidden selected>Pilih Tingkat</option>
                                    <option value="VII" {{ $kelas->tingkat == 'VII' ? 'selected' : '' }}>VII</option>
                                    <option value="VIII" {{ $kelas->tingkat == 'VIII' ? 'selected' : '' }}>VIII</option>
                                    <option value="IX" {{ $kelas->tingkat == 'IX' ? 'selected' : '' }}>IX</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama_kelas" class="form-label">Nama Kelas</label>
                                <select class="form-control" id="nama_kelas" name="nama_kelas" required>
                                    <option value="" disabled hidden selected>Pilih Nama Kelas</option>
                                    <option value="A" {{ $kelas->nama_kelas == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ $kelas->nama_kelas == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="C" {{ $kelas->nama_kelas == 'C' ? 'selected' : '' }}>C</option>
                                    <option value="D" {{ $kelas->nama_kelas == 'D' ? 'selected' : '' }}>D</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group-row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('kelas.index') }}" class="btn btn-secondary ml-3">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
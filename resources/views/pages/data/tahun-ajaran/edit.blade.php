@extends('layouts.app')

@section('title', 'Edit Tahun Ajaran')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Tahun Ajaran</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('tahun-ajaran.update', $tahunAjaran->id_thn_ajaran) }}" method="post">
                @csrf
                @method('put')
                <div class="mb-3">
                    <label for="tahun_ajaran_mulai" class="form-label">Tahun Ajaran Mulai</label>
                    <input type="number" class="form-control @error('tahun_ajaran_mulai') is-invalid @enderror" id="tahun_ajaran_mulai" name="tahun_ajaran_mulai"
                        value="{{ $tahunAjaran->tahun_ajaran_mulai }}" required maxlength="4">
                    @error('tahun_ajaran_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="tahun_ajaran_selesai" class="form-label">Tahun Ajaran Selesai</label>
                    <input type="number" class="form-control @error('tahun_ajaran_selesai') is-invalid @enderror" id="tahun_ajaran_selesai" name="tahun_ajaran_selesai"
                        value="{{ $tahunAjaran->tahun_ajaran_selesai }}" readonly maxlength="4">
                    @error('tahun_ajaran_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{-- <div class="mb-3">
                    <label for="semester" class="form-label">Semester</label>
                    <select class="form-select @error('semester') is-invalid @enderror" id="semester" name="semester" required>
                        <option value="Ganjil" {{ $tahunAjaran->semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ $tahunAjaran->semester == 'Genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                    @error('semester')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> --}}
                <div class="mb-3">
                    <label for="semester" class="form-label">Semester</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input @error('semester') is-invalid @enderror" type="radio" name="semester" id="ganjil" value="Ganjil" {{ $tahunAjaran->semester == 'Ganjil' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="ganjil">
                                Ganjil
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input @error('semester') is-invalid @enderror" type="radio" name="semester" id="genap" value="Genap" {{ $tahunAjaran->semester == 'Genap' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="genap">
                                Genap
                            </label>
                        </div>
                    </div>
                    @error('semester')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                
                <div class="form-group-row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('tahun-ajaran.index') }}" class="btn btn-secondary ml-3">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

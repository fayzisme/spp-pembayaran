@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Siswa</h3>
            </div>
            <div class="card-body">
                @error('username2')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('password2')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @csrf
                <form action="{{ route('siswa.update', $siswa->id_siswa) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nis" class="form-label">NIS</label>
                            <input type="number" class="form-control @error('nis') is-invalid @enderror" id="nis" name="nis" value="{{ $siswa->nis }}" oninput="updateUsername()">
                            @error('nis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_thn_ajaran" class="form-label">Tahun Ajaran</label>
                                <select class="form-control @error('id_thn_ajaran') is-invalid @enderror" id="id_thn_ajaran"
                                    name="id_thn_ajaran" required>
                                    <option value="" disabled hidden selected>Pilih Tahun Ajaran</option>
                                    @foreach ($tahunAjaran as $item)
                                    <option value="{{ $item->id_thn_ajaran }}" {{ $item->id_thn_ajaran == $siswa->tahunAjaran->id_thn_ajaran ? 'selected' : '' }}>{{ $item->thn_ajaran . ' - ' . $item->semester }}</option>
                                    @endforeach
                                </select>
                                @error('id_thn_ajaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                        </div>
                        <input type="hidden" name="username2" id="username2" value="{{ $siswa->user->username }}">
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ $siswa->user->username }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                                <select class="form-control @error('id_kelas') is-invalid @enderror" id="kelas"
                                    name="id_kelas" required>
                                    <option value="" disabled hidden selected>Pilih Kelas</option>
                                    @foreach ($kelas as $item)
                                        <option value="{{ $item->id_kelas }}" {{ $item->id_kelas == $siswa->kelas->id_kelas ? 'selected' : '' }}>{{ $item->tingkat . $item->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_kelas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                        </div>
                        <input type="hidden" name="password2" value="{{ env('SISWA_DEFAULT_PASSWORD') }}">
                        {{-- <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="text" class="form-control" id="password" name="password" value="{{ env('SISWA_DEFAULT_PASSWORD') }}" readonly>
                        </div> --}}
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" readonly
                                    id="password" name="password" required value="{{ env('SISWA_DEFAULT_PASSWORD') }}">
                                <span class="input-group-text cursor-pointer" id="toggle-password"><i class="bx bx-hide"></i></span>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror" id="tgl_lahir" name="tgl_lahir" value="{{ $siswa->tgl_lahir }}">
                            @error('tgl_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ $siswa->nama }}">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" name="tempat_lahir" value="{{ $siswa->tempat_lahir }}">
                            @error('tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $siswa->user->email }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- <div class="col-md-6 mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-control @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" value="{{ $siswa->jenis_kelamin }}"
                                name="jenis_kelamin" required>
                                <option value="" disabled hidden selected>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ "Laki-laki" == $siswa->jenis_kelamin ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ "Perempuan" == $siswa->jenis_kelamin ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}
                        <div class="col-md-6 mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-control @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="" disabled hidden selected>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == "Laki-laki" ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == "Perempuan" ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_hp" class="form-label">No. HP</label>
                            <input type="number" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ $siswa->no_hp }}">
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nama_wali" class="form-label">Nama Wali</label>
                            <input type="text" class="form-control @error('nama_wali') is-invalid @enderror" id="nama_wali" name="nama_wali" value="{{ $siswa->nama_wali }}">
                            @error('nama_wali')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="" disabled hidden selected>Pilih Status</option>
                                <option value="Aktif" {{ old('status', $siswa->status) == "Aktif" ? 'selected' : '' }}>Aktif</option>
                                <option value="Non-aktif" {{ old('status', $siswa->status) == "Non-aktif" ? 'selected' : '' }}>Non-aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}
                        {{-- <div class="col-md-6 mb-3">
                            <label for="agama" class="form-label">Agama</label>
                            <input type="text" class="form-control @error('agama') is-invalid @enderror" id="agama" name="agama" value="{{ $siswa->agama }}">
                            @error('agama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}
                        <div class="col-md-6 mb-3">
                            <label for="agama" class="form-label">Agama</label>
                            <select class="form-control @error('agama') is-invalid @enderror" id="agama" name="agama" required>
                                <option value="" disabled hidden selected>Pilih Agama</option>
                                <option value="Islam" {{ "Islam" == old('agama', $siswa->agama) ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ "Kristen" == old('agama', $siswa->agama) ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ "Katolik" == old('agama', $siswa->agama) ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ "Hindu" == old('agama', $siswa->agama) ? 'selected' : '' }}>Hindu</option>
                                <option value="Budha" {{ "Budha" == old('agama', $siswa->agama) ? 'selected' : '' }}>Budha</option>
                                <option value="Konghucu" {{ "Konghucu" == old('agama', $siswa->agama) ? 'selected' : '' }}>Konghucu</option>
                            </select>
                            @error('agama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Foto</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat">{{ $siswa->alamat }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group-row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('siswa.index') }}" class="btn btn-secondary ml-3">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan JavaScript di bagian bawah halaman atau dalam tag <script> -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('toggle-password');
            const icon = togglePassword.querySelector('i');
    
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
    
                // Toggle the icon class
                if (type === 'text') {
                    icon.classList.remove('bx-hide');
                    icon.classList.add('bx-show');
                } else {
                    icon.classList.remove('bx-show');
                    icon.classList.add('bx-hide');
                }
            });
        });
    </script>
    
    <!-- Opsional: Tambahkan CSS -->
    <style>
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
    
@endsection

@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title ">Tambah Data Siswa</h4>
                    {{-- <a href="{{ route('siswa.index') }}" class="btn btn-secondary mt-3">Batal</a> --}}
                </div>
                <div class="card-body">
                    <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data">
                        @error('username2')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        @error('password2')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nis" class="form-label">NIS</label>
                                <input type="number" class="form-control @error('nis') is-invalid @enderror" id="nis"
                                    name="nis" required oninput="updateUsername()" value="{{ old('nis') }}">
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
                                        <option value="{{ $item->id_thn_ajaran }}" {{ $item->id_thn_ajaran == old('id_thn_ajaran') || $item->id_thn_ajaran == $selected_thn_ajaran->id_thn_ajaran ? 'selected' : '' }}>{{ $item->thn_ajaran . ' - ' . $item->semester  }}</option>
                                    @endforeach
                                </select>
                                @error('id_thn_ajaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <input type="hidden" name="username2" id="username2" value="{{ old('username2') }}">
                            <div class="col-md-6 mb-3">
                                <label for="nis" class="form-label">Username</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror"
                                    id="username" name="username" required disabled value="{{ old('username') }}">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kelas" class="form-label">Kelas</label>
                                <select class="form-control @error('id_kelas') is-invalid @enderror" id="kelas"
                                    name="id_kelas" required>
                                    <option value="" disabled hidden selected>Pilih Kelas</option>
                                    @foreach ($kelas as $item)
                                        <option value="{{ $item->id_kelas }}" {{ $item->id_kelas == old('id_kelas') ? 'selected' : '' }}>{{ $item->tingkat . $item->nama_kelas }}
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
                                <input type="text" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required value="{{ env('SISWA_DEFAULT_PASSWORD') }}"
                                    disabled >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> --}}
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required value="{{ env('SISWA_DEFAULT_PASSWORD') }}" readonly>
                                    <span class="input-group-text cursor-pointer" id="toggle-password"><i class="bx bx-hide"></i></span>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            

                            <div class="col-md-6 mb-3">
                                <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror"
                                    id="tgl_lahir" name="tgl_lahir" required value="{{ old('tgl_lahir') }}">
                                @error('tgl_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="nama" name="nama" required value="{{ old('nama') }}">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                                    id="tempat_lahir" name="tempat_lahir" required value="{{ old('tempat_lahir') }}">
                                @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" required value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-control @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin"
                                    name="jenis_kelamin" required>
                                    <option value="" disabled hidden selected>Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ "Laki-laki" == old('jenis_kelamin') ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ "Perempuan" == old('jenis_kelamin') ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="no_hp" class="form-label">No. HP</label>
                                <input type="number" class="form-control @error('no_hp') is-invalid @enderror"
                                    id="no_hp" name="no_hp" required value="{{ old('no_hp') }}">
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama_wali" class="form-label">Nama Wali</label>
                                <input type="text" class="form-control @error('nama_wali') is-invalid @enderror"
                                    id="nama_wali" name="nama_wali" required value="{{ old('nama_wali') }}">
                                @error('nama_wali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="" disabled hidden selected>Pilih Status</option>
                                    <option value="Aktif" {{ "Aktif" == old('status') ? 'selected' : '' }}>Aktif</option>
                                    <option value="Non-aktif" {{ "Non-aktif" == old('status') ? 'selected' : '' }}>Non-aktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> --}}
                            <div class="col-md-6 mb-3">
                                <label for="agama" class="form-label">Agama</label>
                                <select class="form-control @error('agama') is-invalid @enderror" id="agama"
                                    name="agama" required>
                                    <option value="" disabled hidden selected>Pilih Agama</option>
                                    <option value="Islam" {{ "Islam" == old('agama') ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen" {{ "Kristen" == old('agama') ? 'selected' : '' }}>Kristen</option>
                                    <option value="Katolik" {{ "Katolik" == old('agama') ? 'selected' : '' }}>Katolik</option>
                                    <option value="Hindu" {{ "Hindu" == old('agama') ? 'selected' : '' }}>Hindu</option>
                                    <option value="Budha" {{ "Budha" == old('agama') ? 'selected' : '' }}>Budha</option>
                                    <option value="Konghucu" {{ "Konghucu" == old('agama') ? 'selected' : '' }}>Konghucu</option>
                                </select>
                                @error('agama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Foto</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" >
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" required>{{ old('alamat') }}</textarea>
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

                        {{--  --}}
                        <p></p>
                        <div class="mb-3 col-12 mb-0">
                            <div class="alert alert-primary">
                              <h6 class="alert-heading fw-bold mb-1">Username sesuai dengan NIS</h6>
                              <p class="mb-0">Password : abcd1234</p>
                            </div>
                          </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('toggle-password').addEventListener('click', function () {
            var passwordInput = document.getElementById('password');
            var icon = this.querySelector('i');
            if (passwordInput.type === 'text') {
                passwordInput.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            } else {
                passwordInput.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            }
        });
    </script>
    
    <!-- Include Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

@endsection

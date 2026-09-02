@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Profile Details</h5>
                <!-- Account -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        {{-- <img src="{{ asset('storage/images/' . $user->image) }}" alt="user-avatar" class="d-block rounded"
                        height="100" width="100" id="uploadedAvatar" /> --}}

                        <img src="{{ asset('images/' . Auth::user()->image) }}" alt="user-avatar" class="d-block rounded"
                            height="100" width="100" id="uploadedAvatar" />
                        <div class="button-wrapper">
                            <form action="{{ route('profile.update-image') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="account-image"
                                        class="form-label
                                    d-none">Pilih Foto</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        onchange="document.getElementById('uploadedAvatar').src = window.URL.createObjectURL(this.files[0])"
                                        oninput="this.nextElementSibling.innerText = this.files[0].name"
                                        id="account-image" name="image" accept="image/*" />
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary account-image-save mb-4">
                                    <i class="bx bx-save d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Simpan</span>
                                </button>
                                <button type="reset"
                                onclick="document.getElementById('uploadedAvatar').src = '{{ asset('images/' . Auth::user()->image) }}'"
                                class="btn btn-secondary account-image-reset mb-4">
                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Batal</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <form id="formAccountSettings" method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        @if (Auth::user()->id_role == 3)
                            {{-- nama,tempat_lahir,tgl_lahir,jenis_kelamin,agama,no_hp,nama_wali --}}
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input class="form-control @error('nama') is-invalid @enderror" type="text"
                                        id="nama" name="nama" value="{{ Auth::user()->siswa->nama }}" autofocus />
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                    <input class="form-control @error('tempat_lahir') is-invalid @enderror" type="text"
                                        id="tempat_lahir" name="tempat_lahir" value="{{ Auth::user()->siswa->tempat_lahir }}"
                                        autofocus />
                                    @error('tempat_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                    <input class="form-control @error('tgl_lahir') is-invalid @enderror" type="date"
                                        id="tgl_lahir" name="tgl_lahir" value="{{ Auth::user()->siswa->tgl_lahir }}"
                                        autofocus />
                                    @error('tgl_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                        id="jenis_kelamin" name="jenis_kelamin" autofocus>
                                        <option value="" hidden selected disabled>Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" @if (Auth::user()->siswa->jenis_kelamin == 'Laki-laki') selected @endif>Laki-laki</option>
                                        <option value="Perempuan" @if (Auth::user()->siswa->jenis_kelamin == 'Perempuan') selected @endif>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="agama" class="form-label">Agama</label>
                                    <select class="form-select @error('agama') is-invalid @enderror" id="agama"
                                        name="agama" autofocus>
                                        <option value="" hidden selected disabled>Pilih Agama</option>
                                        <option value="Islam" @if (Auth::user()->siswa->agama == 'Islam') selected @endif>Islam</option>
                                        <option value="Kristen" @if (Auth::user()->siswa->agama == 'Kristen') selected @endif>Kristen</option>
                                        <option value="Katolik" @if (Auth::user()->siswa->agama == 'Katolik') selected @endif>Katolik</option>
                                        <option value="Hindu" @if (Auth::user()->siswa->agama == 'Hindu') selected @endif>Hindu</option>
                                        <option value="Budha" @if (Auth::user()->siswa->agama == 'Budha') selected @endif>Budha</option>
                                        <option value="Konghucu" @if (Auth::user()->siswa->agama == 'Konghucu') selected @endif>Konghucu</option>
                                    </select>
                                    @error('agama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="no_hp" class="form-label">No. HP</label>
                                    <input class="form-control @error('no_hp') is-invalid @enderror" type="text"
                                        id="no_hp" name="no_hp" value="{{ Auth::user()->siswa->no_hp }}" autofocus />
                                    @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="nama_wali" class="form-label">Nama Wali</label>
                                    <input class="form-control @error('nama_wali') is-invalid @enderror" type="text"
                                        id="nama_wali" name="nama_wali" value="{{ Auth::user()->siswa->nama_wali }}"
                                        autofocus />
                                    @error('nama_wali')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- alamat --}}
                                <div class="mb-3 col-md-6">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                                        name="alamat" rows="3" autofocus>{{ Auth::user()->siswa->alamat }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <div class="row">
                                {{-- username, email, password, nama, alamat, no_hp --}}
                                <div class="mb-3 col-md-6">
                                    <label for="username" class="form-label">Username</label>
                                    <input class="form-control @error('username') is-invalid @enderror" type="text"
                                        id="username" name="username" value="{{ Auth::user()->username }}" autofocus />
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email"
                                        id="email" name="email" value="{{ Auth::user()->email }}" autofocus />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input class="form-control @error('nama') is-invalid @enderror" type="text"
                                        id="nama" name="nama" value="{{ Auth::user()->petugas->nama }}"
                                        autofocus />
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <input class="form-control @error('alamat') is-invalid @enderror" type="text"
                                        id="alamat" name="alamat" value="{{ Auth::user()->petugas->alamat }}"
                                        autofocus />
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="no_hp" class="form-label">No. HP</label>
                                    <input class="form-control @error('no_hp') is-invalid @enderror" type="text"
                                        id="no_hp" name="no_hp" value="{{ Auth::user()->petugas->no_hp }}"
                                        autofocus />
                                    @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Simpan</button>
                            <button type="reset" class="btn btn-secondary me-2">Batal</button>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
        </div>
        {{-- Ubah Password --}}
        <div class="col-md-6">
            <div class="card mb-4">
                <h5 class="card-header">Ubah Password</h5>
                <div class="card-body">
                    <form id="formChangePassword" method="POST" action="{{ route('profile.change-password') }}">
                        @csrf
                        @method('PUT')
                        {{-- <div class="form-password-toggle">
                            <label class="form-label" for="basic-default-password12">Password</label>
                            <div class="input-group">
                              <input
                                type="password"
                                class="form-control"
                                id="basic-default-password12"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="basic-default-password2"
                              />
                              <span id="basic-default-password2" class="input-group-text cursor-pointer"
                                ><i class="bx bx-hide"></i
                              ></span>
                            </div>
                          </div> --}}
                        {{-- <div class="mb-3">
                            <label for="currentPassword" class="form-label">Password Saat Ini</label>
                            <input class="form-control @error('currentPassword') is-invalid @enderror" type="password"
                                id="currentPassword" name="currentPassword"/>
                            @error('currentPassword')
                                <div class="invalid-feedback">{{ $message }}</div>   
                            @enderror
                            
                        </div> --}}
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="currentPassword">Password Saat Ini</label>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" name="currentPassword"
                                    class="form-control @error('currentPassword') is-invalid @enderror" id="currentPassword" required
                                    autocomplete="current-password">
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide" id="toggleCurrentPassword" onclick="togglePasswordVisibility('currentPassword', 'toggleCurrentPassword')"></i></span>
                                @error('currentPassword')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input class="form-control @error('password') is-invalid @enderror" type="password"
                                id="password" name="password" />
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input class="form-control @error('password_confirmation') is-invalid @enderror"
                                type="password" id="password_confirmation" name="password_confirmation" />
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password Baru</label>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password" required
                                    autocomplete="new-password">
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide" id="togglePassword" onclick="togglePasswordVisibility('password', 'togglePassword')"></i></span>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" required
                                    autocomplete="new-password">
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide" id="togglePasswordConfirmation" onclick="togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmation')"></i></span>
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Simpan</button>
                            <button type="reset" class="btn btn-secondary me-2">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Hapus Akun --}}
        {{-- <div class="col-md-6">
            <div class="card mb-4">
                <h5 class="card-header">Hapus Akun</h5>
                <div class="card-body">
                    <form id="formDeleteAccount" method="POST" action="{{ route('profile.delete-account') }}">
                        @csrf
                        @method('DELETE')
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
                            <input class="form-control @error('confirmPassword') is-invalid @enderror" type="password"
                                id="confirmPassword" name="confirmPassword" />
                            @error('confirmPassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-2">
                            <button type="submit show_confirm" class="btn btn-danger me-2">Hapus Akun</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
    </div>

    {{-- @section('scripts')
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                input.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        }
    </script>
@endsection --}}
@endsection
 @section('scripts')
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bx-hide');
            icon.classList.add('bx-show');
        } else {
            input.type = 'password';
            icon.classList.remove('bx-show');
            icon.classList.add('bx-hide');
        }
    }
</script>
@endsection
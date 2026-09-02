@extends('layouts.blank')

@section('title', 'Forget Password Link')

@section('content')

<style>
    body {
        background: linear-gradient(to right,#a1c5dd, #7ab3d4) !important;   
            /* background-color: #e8f0f3;  */
            font-family: Arial, sans-serif; /* Opsi: Ganti dengan font yang diinginkan */
            margin: 0;
            padding: 0;
            height: 100vh; /* Menjamin tinggi penuh */
        }

        .container-xxl {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%; /* Menjamin tinggi penuh */
            background: linear-gradient(to right,#a1c5dd, #7ab3d4) !important;   
        }

        .authentication-wrapper {
            background-color: transparent; /* Membuat wrapper transparan */
        }
  
      .card {
          background: rgba(255, 255, 255, 0.8); /* Untuk efek transparan */
          border-radius: 10px;
      }
  </style>

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Forgot Password -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="index.html" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <img src="{{ asset('assets/img/logo1.png') }}" alt="Logo Sipemba" width="80">
                                    <span class="app-brand-text demo menu-text fw-bolder ms-2">Sipemba</span>
                                </a>
                            </div>
                            <!-- /Logo -->
                            <h4 class="mb-2">Reset Password</h4>

                            @if (Session::has('message'))
                                <div class="alert alert-success" role="alert">
                                    {{ Session::get('message') }}
                                </div>
                            @endif
                            <form id="formAuthentication" class="mb-3" action="{{ route('reset.password.post') }}" method="POST">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <input type="hidden" name="email" value="{{ $email }}">
                                <div class="container mt-5">
                                    <div class="mb-3 form-password-toggle">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter your password" required />
                                            <span class="input-group-text cursor-pointer" onclick="togglePasswordVisibility('password', this)"><i class="bx bx-hide"></i></span>
                                            @if ($errors->has('password'))
                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-3 form-password-toggle">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Enter your password" required />
                                            <span class="input-group-text cursor-pointer" onclick="togglePasswordVisibility('password_confirmation', this)"><i class="bx bx-hide"></i></span>
                                            @if ($errors->has('password_confirmation'))
                                                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if(Session::has('success'))
                                <script>
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: "{{ Session::get('success') }}",
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    });
                                </script>
                            @endif

                                <button type="submit" class="btn btn-primary d-grid w-100">Reset Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
<script>
    function togglePasswordVisibility(fieldId, element) {
        var input = document.getElementById(fieldId);
        if (input.type === "password") {
            input.type = "text";
            element.querySelector('i').classList.remove('bx-hide');
            element.querySelector('i').classList.add('bx-show');
        } else {
            input.type = "password";
            element.querySelector('i').classList.remove('bx-show');
            element.querySelector('i').classList.add('bx-hide');
        }
    }
</script>
@endsection


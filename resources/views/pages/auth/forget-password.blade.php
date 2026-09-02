@extends('layouts.blank')

@section('title', 'Forget Password')

@section('content')
<style>
    body {
        background: linear-gradient(to right,#a1c5dd, #7ab3d4) !important;   
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
                                
                                <img src="{{ asset('assets/img/logo1.png') }}" alt="Logo Sipemba" width="80">
                                <span class="app-brand-text demo menu-text fw-bolder ms-2">Sipemba</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h5 class="mb-2">Lupa Password? 🔒</h5>
                        <p class="mb-4">Masukkan Email yang Terdaftar</p>
                        @if (Session::has('message'))
                            <div class="alert alert-success" role="alert">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                        <form id="formAuthentication" class="mb-3" action="{{ route('forget.password.post') }}"
                            method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Enter your email" autofocus required autocomplete="email" />
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary d-grid w-100">Send Reset Link</button>
                        </form>
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                                <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                                Back to login
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Forgot Password -->
            </div>
        </div>
    </div>
@endsection

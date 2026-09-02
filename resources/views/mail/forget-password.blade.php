<div class="container">
    <h1 class="mt-4">Reset Password</h1>

    <p>Kami telah menerima permintaan untuk mereset password akun Anda. Untuk melanjutkan proses </p>
    <p>reset password, silahkan klik tautan di bawah ini: </p>
    <p>Klik tombol di bawah untuk mengatur ulang kata sandi Anda.</p>
    <a href="{{ route('reset.password.get', $token) }}" class="btn btn-primary">Reset Password</a>

    <p>Namun bila Anda tidak pernah meminta proses ini, maka kami berharap Anda mengabaikan email ini.</p>

    <p>Terimakasih,<br>
        {{ config('app.name') }}</p>

    <p>Jika Anda kesulitan mengklik tautan "Reset Password", salin dan tempel URL di bawah ini ke web browser Anda
        </p>
    <a href="{{ route('reset.password.get', $token) }}">{{ route('reset.password.get', $token) }}</a>
</div>

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Write code on Method
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showForgetPasswordForm()
    {
        return view('pages.auth.forget-password');
    }

    /**
     * Write code on Method
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $tokenRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if ($tokenRecord) {
            return back()->with('message', 'A password reset link has already been sent to this email address.');
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('mail.forget-password', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });
        // Mail::to($request->email)->send(new ResetPasswordMail($token));
        return back()->with('message', 'We have e-mailed your password reset link!');
    }

    /**
     * Write code on Method
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showResetPasswordForm($token)
    {
        $tokenData = DB::table('password_reset_tokens')
            ->where('token', $token)->first();

        if (!$tokenData) {
            return redirect()->to('/login')->with('message', 'Invalid token.');
        }

        $tokenCreated = Carbon::parse($tokenData->created_at);
        $tokenLife = $tokenCreated->diffInMinutes(Carbon::now());

        if ($tokenLife > 60) {
            DB::table('password_reset_tokens')->where('token', $token)->delete();
            return redirect()->to('/login')->with('message', 'Your token has expired. Please request a new one.');
        }
        return view('pages.auth.forget-password-link', ['token' => $token, 'email' => $tokenData->email]);
    }

    /**
     * Write code on Method
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        alert()->success('Berhasil', 'Password berhasil diubah');
        return redirect('/login')->with('message', 'Password Berhasil Diubah!');
    }
}
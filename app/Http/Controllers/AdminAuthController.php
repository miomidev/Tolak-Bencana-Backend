<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Handle admin login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Email atau username wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginField = $request->input('login');
        $password = $request->input('password');

        // Check if input is email or username
        $loginType = filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($loginType, $loginField)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()->withErrors([
                'login' => 'Kredensial yang Anda masukkan salah.',
            ])->withInput($request->only('login', 'remember'));
        }

        // Verify role
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            return back()->withErrors([
                'login' => 'Akses ditolak. Anda tidak memiliki hak akses Administrator.',
            ])->withInput($request->only('login', 'remember'));
        }

        // Login user
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Show forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('admin.forgot-password');
    }

    /**
     * Send OTP reset code (simulation for dev).
     */
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Alamat email tidak terdaftar di sistem.',
        ]);

        $email = $request->input('email');
        $otp = sprintf("%06d", mt_rand(100000, 999999));

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($otp),
                'created_at' => now()
            ]
        );

        // Simulated OTP delivery: save in session so UI can show it to developer/tester
        return redirect()->route('admin.forgot-password.otp', ['email' => $email])
            ->with('simulated_otp', $otp)
            ->with('info', 'Kode OTP pemulihan kata sandi telah disimulasikan.');
    }

    /**
     * Show OTP verification form.
     */
    public function showOtpForm(Request $request)
    {
        $email = $request->query('email');
        if (!$email) {
            return redirect()->route('admin.forgot-password');
        }
        return view('admin.forgot-password-otp', compact('email'));
    }

    /**
     * Verify OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size' => 'Kode OTP harus 6 digit.',
        ]);

        $email = $request->input('email');
        $otp = $request->input('otp');

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record || !Hash::check($otp, $record->token)) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kedaluwarsa.']);
        }

        // OTP verified, redirect to password reset form with hashed email/token to proceed safely
        return redirect()->route('admin.reset-password', ['email' => $email, 'token' => md5($email . $record->token)]);
    }

    /**
     * Show Reset Password form.
     */
    public function showResetPasswordForm(Request $request)
    {
        $email = $request->query('email');
        $token = $request->query('token');

        if (!$email || !$token) {
            return redirect()->route('admin.forgot-password');
        }

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();
        if (!$record || md5($email . $record->token) !== $token) {
            return redirect()->route('admin.forgot-password')->withErrors(['email' => 'Sesi pemulihan tidak valid.']);
        }

        return view('admin.reset-password', compact('email', 'token'));
    }

    /**
     * Reset the password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $email = $request->input('email');
        $token = $request->input('token');

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();
        if (!$record || md5($email . $record->token) !== $token) {
            return redirect()->route('admin.forgot-password')->withErrors(['email' => 'Sesi pemulihan tidak valid.']);
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            $user->password = Hash::make($request->input('password'));
            $user->save();
        }

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return redirect()->route('admin.login')->with('success', 'Kata sandi berhasil diubah. Silakan masuk kembali.');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }

    /**
     * Login user using email or username.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($loginType, $request->login)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Kredensial yang diberikan salah.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    /**
     * Logout user (revoke token).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }

    /**
     * Send password reset OTP.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $otp = sprintf('%06d', mt_rand(1, 999999));

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        // TODO: Send OTP via Email using Mail facade
        // For now, return OTP in response for development/testing
        return response()->json([
            'message' => 'Kode OTP berhasil dibuat.',
            'otp' => $otp, // HAPUS INI JIKA SUDAH PRODUCTION / PAKAI SMTP
        ]);
    }

    /**
     * Verify OTP.
     */
    public function verifyResetToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record || ! Hash::check($request->otp, $record->token)) {
            throw ValidationException::withMessages([
                'otp' => ['Kode OTP tidak valid atau sudah kedaluwarsa.'],
            ]);
        }

        return response()->json([
            'message' => 'Kode OTP valid.',
        ]);
    }

    /**
     * Reset the password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record || ! Hash::check($request->otp, $record->token)) {
            throw ValidationException::withMessages([
                'otp' => ['Kode OTP tidak valid.'],
            ]);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'message' => 'Kata sandi berhasil diatur ulang.',
        ]);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048', // 2MB max
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->avatar_url) {
                $oldPath = str_replace(asset('storage/'), '', $user->avatar_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('photo')->store('avatars', 'public');
            $user->avatar_url = asset('storage/'.$path);
        }

        $user->save();

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user,
        ]);
    }

    /**
     * Change password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Kata sandi saat ini salah.'],
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Kata sandi berhasil diperbarui',
        ]);
    }

    /**
     * Delete user profile photo.
     */
    public function deleteProfilePhoto(Request $request)
    {
        $user = $request->user();

        if ($user->avatar_url) {
            $oldPath = str_replace(asset('storage/'), '', $user->avatar_url);
            Storage::disk('public')->delete($oldPath);
            $user->avatar_url = null;
            $user->save();
        }

        return response()->json([
            'message' => 'Foto profil berhasil dihapus',
            'user' => $user,
        ]);
    }

    /**
     * Login with Google using ID Token from Flutter app.
     * Flutter sends the Google ID Token, backend verifies it via Socialite.
     */
    public function googleLogin(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
        ]);

        try {
            // Verify the Google ID token using Socialite
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($request->id_token);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Token Google tidak valid atau sudah kedaluwarsa.',
                'error' => $e->getMessage(),
            ], 401);
        }

        // Find existing user by google_id or email
        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user) {
            // Try to find by email (user might have registered manually before)
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Link existing account with Google
                $user->google_id = $googleUser->getId();
                if (! $user->avatar_url && $googleUser->getAvatar()) {
                    $user->avatar_url = $googleUser->getAvatar();
                }
                $user->save();
            } else {
                // Create new user from Google account
                $username = $this->generateUniqueUsername($googleUser->getName(), $googleUser->getEmail());

                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'username' => $username,
                    'avatar_url' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                    'role' => 'user',
                    'is_active' => true,
                ]);
            }
        }

        // Check if user is active
        if (isset($user->is_active) && ! $user->is_active) {
            return response()->json([
                'message' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login dengan Google berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    /**
     * Generate a unique username from name or email.
     */
    private function generateUniqueUsername(string $name, string $email): string
    {
        // Try name-based username first
        $base = Str::slug(Str::lower($name), '.');
        $base = preg_replace('/[^a-z0-9._]/', '', $base);
        $base = $base ?: Str::before($email, '@');

        $username = $base;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $base . $counter;
            $counter++;
        }

        return $username;
    }
}

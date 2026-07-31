<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use App\Models\User;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->withCount('reports')
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->q}%")
                        ->orWhere('email', 'like', "%{$request->q}%")
                        ->orWhere('username', 'like', "%{$request->q}%");
                });
            })
            ->when($request->filled('role'), function ($q) use ($request) {
                $q->where('role', $request->role);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')],
            'phone' => 'nullable|string|max:255',
            'role' => 'required|in:superadmin,admin,user',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan akun lain.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan akun lain.',
            'role.in' => 'Role tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'phone' => $data['phone'] ?? null,
            'role' => $data['role'],
            'is_active' => true,
            'password' => Hash::make($data['password']),
        ]);

        Audit::log('user.created', 'Akun "'.$data['name'].'" ('.$data['role'].') berhasil dibuat.', $user);

        return redirect()->route('admin.users.edit', $user)
            ->with('success', 'Akun "'.$data['name'].'" berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $user->loadCount('reports');
        $user->load('notificationSetting');

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Proteksi: admin tidak boleh menurunkan akun sendiri
        if ($user->id === auth()->id() && $request->input('role') !== $user->role) {
            return back()->withErrors([
                'role' => 'Anda tidak dapat mengubah role akun sendiri.',
            ]);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'username' => ['required', 'string', Rule::unique('users', 'username')->ignore($user->id)],
            'phone' => 'nullable|string|max:255',
            'role' => 'required|in:superadmin,admin,user',
            'is_active' => 'sometimes|boolean',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan akun lain.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan akun lain.',
            'role.in' => 'Role tidak valid.',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $user->update($data);

        Audit::log('user.updated', 'Data akun "'.$data['name'].'" diperbarui.', $user);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna "'.$data['name'].'" berhasil diperbarui.');
    }

    public function updateNotifications(Request $request, User $user)
    {
        $data = $request->validate([
            'flood_alert' => 'sometimes|boolean',
            'report_update' => 'sometimes|boolean',
            'emergency_alert' => 'sometimes|boolean',
            'location_alert' => 'sometimes|boolean',
        ]);

        $setting = NotificationSetting::firstOrCreate(['user_id' => $user->id]);

        $setting->update([
            'flood_alert' => $request->boolean('flood_alert'),
            'report_update' => $request->boolean('report_update'),
            'emergency_alert' => $request->boolean('emergency_alert'),
            'location_alert' => $request->boolean('location_alert'),
        ]);

        Audit::log('user.notification_settings', 'Preferensi notifikasi akun "'.$user->name.'" diperbarui.', $user);

        return redirect()->back()
            ->with('success', 'Preferensi notifikasi untuk "'.$user->name.'" berhasil disimpan.');
    }

    public function toggle(User $user)
    {
        // Proteksi: admin tidak boleh menonaktifkan akun sendiri
        if ($user->id === auth()->id()) {
            return back()->withErrors([
                'toggle' => 'Anda tidak dapat menonaktifkan akun sendiri.',
            ]);
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        Audit::log('user.toggled', 'Akun "'.$user->name.'" '.($user->is_active ? 'diaktifkan' : 'dinonaktifkan').'.', $user);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun "'.$user->name.'" '.($user->is_active ? 'diaktifkan' : 'dinonaktifkan').'.');
    }

    public function resetPassword(User $user)
    {
        $password = $this->generatePassword();
        $user->password = Hash::make($password);
        $user->save();

        Audit::log('user.password_reset', 'Kata sandi akun "'.$user->name.'" direset oleh admin.', $user);

        return redirect()->route('admin.users.edit', $user)
            ->with('info', 'Kata sandi baru untuk "'.$user->name.'" adalah: <strong>'.$password.'</strong> (catat dan simpan, hanya ditampilkan sekali).');
    }

    private function generatePassword(): string
    {
        return 'Kwd'.strtoupper(\Str::random(6)).'@'.random_int(10, 99);
    }
}

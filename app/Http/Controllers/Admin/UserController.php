<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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

    public function edit(User $user)
    {
        $user->loadCount('reports');

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

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna "'.$data['name'].'" berhasil diperbarui.');
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

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun "'.$user->name.'" '.($user->is_active ? 'diaktifkan' : 'dinonaktifkan').'.');
    }

    public function resetPassword(User $user)
    {
        $password = $this->generatePassword();
        $user->password = Hash::make($password);
        $user->save();

        return redirect()->route('admin.users.edit', $user)
            ->with('info', 'Kata sandi baru untuk "'.$user->name.'" adalah: <strong>'.$password.'</strong> (catat dan simpan, hanya ditampilkan sekali).');
    }

    private function generatePassword(): string
    {
        return 'Kwd'.strtoupper(\Str::random(6)).'@'.random_int(10, 99);
    }
}

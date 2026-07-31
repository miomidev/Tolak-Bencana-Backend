@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')
@section('header_title', 'Manajemen Pengguna')

@section('content')
<div class="mb-6">
    <h3 class="text-lg font-extrabold text-[#1F2937]">Daftar Pengguna</h3>
    <p class="text-sm text-gray-400">Kelola akun pengguna aplikasi KALIWEDI</p>
</div>

<!-- Filter bar -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[220px]">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / email / username..." class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
        </div>
        <select name="role" class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
            <option value="">Semua Role</option>
            <option value="superadmin" @selected(request('role') === 'superadmin')>Super Admin</option>
            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
            <option value="user" @selected(request('role') === 'user')>Warga</option>
        </select>
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold px-4 py-2.5 rounded-xl transition-colors">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
        @if(request('q') || request('role'))
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-400 hover:text-gray-600"><i class="fa-solid fa-rotate-left"></i> Reset</a>
        @endif
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <th class="px-5 py-3.5">Pengguna</th>
                    <th class="px-5 py-3.5">Username</th>
                    <th class="px-5 py-3.5">Role</th>
                    <th class="px-5 py-3.5">Laporan</th>
                    <th class="px-5 py-3.5">Terdaftar</th>
                    <th class="px-5 py-3.5">Status</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    @php $role = \App\Support\UiHelper::role($user->role); @endphp
                    <tr class="hover:bg-gray-50 transition-colors {{ !$user->is_active ? 'opacity-60' : '' }}">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if($user->avatar_url)
                                    <img src="{{ \Illuminate\Support\Str::startsWith($user->avatar_url, 'http') ? $user->avatar_url : asset('storage/' . str_replace('storage/', '', $user->avatar_url)) }}" class="w-9 h-9 rounded-full object-cover border border-gray-200" alt="">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-[#0F767E] flex items-center justify-center text-white text-xs font-bold shrink-0">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-bold text-[#1F2937] truncate">{{ $user->name }}</p>
                                    <p class="text-[11px] text-gray-400 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-gray-500">{{ $user->username }}</td>
                        <td class="px-5 py-4">
                            <span class="text-[11px] {{ $role['text'] }} {{ $role['bg'] }} border {{ $role['border'] }} px-2.5 py-1 rounded font-bold uppercase">{{ $role['label'] }}</span>
                        </td>
                        <td class="px-5 py-4 text-gray-600">{{ $user->reports_count }}</td>
                        <td class="px-5 py-4 text-gray-400 text-xs">{{ $user->created_at?->format('d M Y') }}</td>
                        <td class="px-5 py-4">
                            @if($user->id === auth()->id())
                                <span class="text-[11px] bg-purple-50 text-purple-600 border border-purple-200 px-2.5 py-1 rounded font-bold">Anda</span>
                            @else
                                <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="relative inline-flex items-center h-6 w-11 rounded-full transition-colors {{ $user->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <span class="inline-block w-4 h-4 transform bg-white rounded-full shadow transition-transform {{ $user->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-[#0F767E] hover:bg-teal-50 p-2 rounded-lg transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-users text-3xl block mb-2 text-gray-300"></i>
                            Tidak ada pengguna yang cocok dengan pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
</div>
@endsection

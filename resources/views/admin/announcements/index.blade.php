@extends('layouts.admin')

@section('title', 'Pemberitahuan')
@section('header_title', 'Pemberitahuan')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-extrabold text-[#1F2937]">Daftar Pemberitahuan</h3>
        <p class="text-sm text-gray-400">Pemberitahuan yang tampil sebagai running text di landing page</p>
    </div>
    <a href="{{ route('admin.announcements.create') }}" class="bg-[#0F767E] hover:bg-[#0a5258] text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Pemberitahuan
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <th class="px-5 py-3.5 w-16">#</th>
                    <th class="px-5 py-3.5">Kunci</th>
                    <th class="px-5 py-3.5">Isi Pemberitahuan</th>
                    <th class="px-5 py-3.5">Urutan</th>
                    <th class="px-5 py-3.5">Status</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($announcements as $announcement)
                    <tr class="hover:bg-gray-50 transition-colors {{ !$announcement->is_active ? 'opacity-60' : '' }}">
                        <td class="px-5 py-4 text-gray-400">{{ $announcements->firstItem() + $loop->index }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-block bg-red-50 text-red-600 border border-red-200 px-2.5 py-1 rounded-md font-extrabold uppercase text-[11px]">{{ $announcement->key }}</span>
                        </td>
                        <td class="px-5 py-4 text-gray-500 max-w-[400px]">{{ Str::limit($announcement->message, 90) }}</td>
                        <td class="px-5 py-4 text-gray-400 text-xs">{{ $announcement->sort_order }}</td>
                        <td class="px-5 py-4">
                            <form action="{{ route('admin.announcements.toggle', $announcement) }}" method="POST">
                                @csrf
                                <button type="submit" class="relative inline-flex items-center h-6 w-11 rounded-full transition-colors {{ $announcement->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}" title="{{ $announcement->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <span class="inline-block w-4 h-4 transform bg-white rounded-full shadow transition-transform {{ $announcement->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </form>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="text-[#0F767E] hover:bg-teal-50 p-2 rounded-lg transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Hapus pemberitahuan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-bullhorn text-3xl block mb-2 text-gray-300"></i>
                            Belum ada pemberitahuan. <a href="{{ route('admin.announcements.create') }}" class="text-[#0F767E] font-bold">Tambah sekarang</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $announcements->links() }}
    </div>
</div>
@endsection

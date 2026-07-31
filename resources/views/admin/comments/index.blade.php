@extends('layouts.admin')

@section('title', 'Moderasi Komentar')
@section('header_title', 'Moderasi Komentar')

@section('content')
<div class="mb-6">
    <h3 class="text-lg font-extrabold text-[#1F2937]">Moderasi Komentar</h3>
    <p class="text-sm text-gray-400">Tinjau dan hapus komentar warga pada laporan</p>
</div>

<!-- Filter bar -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6">
    <form method="GET" action="{{ route('admin.comments.index') }}" class="flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[220px]">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari isi komentar..." class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
        </div>
        <select name="status" class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
            <option value="">Semua Status</option>
            <option value="active" @selected(request('status') === 'active')>Tampil</option>
            <option value="hidden" @selected(request('status') === 'hidden')>Tersembunyi</option>
        </select>
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold px-4 py-2.5 rounded-xl transition-colors">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
        @if(request('q') || request('status'))
            <a href="{{ route('admin.comments.index') }}" class="text-sm text-gray-400 hover:text-gray-600"><i class="fa-solid fa-rotate-left"></i> Reset</a>
        @endif
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <th class="px-5 py-3.5">Komentar</th>
                    <th class="px-5 py-3.5">Penulis</th>
                    <th class="px-5 py-3.5">Pada Laporan</th>
                    <th class="px-5 py-3.5">Status</th>
                    <th class="px-5 py-3.5">Waktu</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($comments as $comment)
                    <tr class="hover:bg-gray-50 transition-colors {{ !$comment->is_active ? 'opacity-60' : '' }}">
                        <td class="px-5 py-4">
                            <p class="text-[#1F2937] line-clamp-2 max-w-md">{{ $comment->comment }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-bold text-[#1F2937]">{{ $comment->user?->name ?? 'Pengguna dihapus' }}</p>
                            <p class="text-[11px] text-gray-400">{{ $comment->user?->email }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <a href="{{ route('admin.reports.show', $comment->report_id) }}" class="text-[#0F767E] hover:underline line-clamp-1 max-w-[220px] block" title="Lihat laporan">
                                <i class="fa-solid fa-file-lines mr-1 text-gray-300"></i>{{ $comment->report?->address ?? 'Laporan dihapus' }}
                            </a>
                        </td>
                        <td class="px-5 py-4">
                            @if($comment->is_active)
                                <span class="text-[11px] text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded font-bold uppercase">Tampil</span>
                            @else
                                <span class="text-[11px] text-gray-600 bg-gray-100 border border-gray-200 px-2.5 py-1 rounded font-bold uppercase">Tersembunyi</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-gray-400 text-xs">{{ $comment->created_at?->format('d M Y H:i') }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.comments.show', $comment) }}" class="text-[#0F767E] hover:bg-teal-50 p-2 rounded-lg transition-colors" title="Lihat detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus komentar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:bg-rose-50 p-2 rounded-lg transition-colors" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-comment-slash text-3xl block mb-2 text-gray-300"></i>
                            Tidak ada komentar yang cocok.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $comments->links() }}
    </div>
</div>
@endsection

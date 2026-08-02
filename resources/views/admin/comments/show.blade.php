@extends('layouts.admin')

@section('title', 'Detail Komentar')
@section('header_title', 'Detail Komentar')

@section('content')
@php $role = \App\Support\UiHelper::role($comment->user?->role ?? 'user'); @endphp

<div class="mb-6">
    <a href="{{ route('admin.comments.index') }}" class="text-sm text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke daftar komentar</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Komentar -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
            <div class="flex items-center justify-between gap-3 mb-5">
                <h3 class="text-lg font-extrabold text-[#1F2937]"><i class="fa-solid fa-comment text-[#0F767E] mr-2"></i> Isi Komentar</h3>
                @if($comment->is_active)
                    <span class="text-[11px] text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded font-bold uppercase">Tampil</span>
                @else
                    <span class="text-[11px] text-gray-600 bg-gray-100 border border-gray-200 px-2.5 py-1 rounded font-bold uppercase">Tersembunyi</span>
                @endif
            </div>
            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $comment->comment }}</p>

            <div class="mt-6 pt-5 border-t border-gray-100 flex items-center gap-3 text-xs text-gray-400">
                <i class="fa-solid fa-clock"></i> Diposting {{ $comment->created_at?->format('d M Y H:i') }} ({{ $comment->created_at?->diffForHumans() }})
            </div>
        </div>

        <!-- Laporan terkait -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h4 class="text-sm font-extrabold text-[#1F2937] mb-4"><i class="fa-solid fa-file-waveform text-[#0F767E] mr-2"></i> Komentar Pada Laporan</h4>
            @php $repStatus = \App\Support\UiHelper::status($comment->report?->status ?? 'pending'); $repSev = \App\Support\UiHelper::severity($comment->report?->severity ?? 'low'); @endphp
            @if($comment->report)
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="text-[11px] {{ $repStatus['text'] }} {{ $repStatus['bg'] }} border {{ $repStatus['border'] }} px-2.5 py-1 rounded font-bold uppercase">{{ $repStatus['label'] }}</span>
                    <span class="text-[11px] {{ $repSev['text'] }} {{ $repSev['bg'] }} border {{ $repSev['border'] }} px-2.5 py-1 rounded font-bold uppercase">Severitas: {{ $repSev['label'] }}</span>
                </div>
                <p class="text-sm text-gray-600">{{ $comment->report->address }}</p>
                <a href="{{ route('admin.reports.show', $comment->report_id) }}" class="inline-flex items-center gap-2 mt-4 text-sm font-bold text-[#0F767E] hover:underline">
                    <i class="fa-solid fa-up-right-from-square"></i> Lihat detail laporan
                </a>
            @else
                <p class="text-sm text-gray-400">Laporan terkait sudah dihapus.</p>
            @endif
        </div>
    </div>

    <!-- Side panel -->
    <div class="space-y-6">
        <!-- Info penulis -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h4 class="text-sm font-extrabold text-[#1F2937] mb-4"><i class="fa-solid fa-user text-[#0F767E] mr-2"></i> Penulis Komentar</h4>
            @if($comment->user)
                <div class="flex items-center gap-3 mb-5">
                    @if($comment->user->avatar_url)
                        <img src="{{ \Illuminate\Support\Str::startsWith($comment->user->avatar_url, 'http') ? $comment->user->avatar_url : asset('storage/' . str_replace('storage/', '', $comment->user->avatar_url)) }}" class="w-14 h-14 rounded-full object-cover border border-gray-200" alt="">
                    @else
                        <div class="w-14 h-14 rounded-full bg-[#0F767E] flex items-center justify-center text-white text-lg font-bold shrink-0">{{ strtoupper(substr($comment->user->name, 0, 2)) }}</div>
                    @endif
                    <div class="min-w-0">
                        <p class="font-extrabold text-[#1F2937] truncate">{{ $comment->user->name }}</p>
                        <span class="text-[11px] {{ $role['text'] }} {{ $role['bg'] }} border {{ $role['border'] }} px-2.5 py-1 rounded font-bold uppercase inline-block mt-1">{{ $role['label'] }}</span>
                    </div>
                </div>
                <dl class="space-y-2.5 text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-400">Email</dt>
                        <dd class="text-gray-700 font-medium text-right truncate">{{ $comment->user->email }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-400">Telepon</dt>
                        <dd class="text-gray-700 font-medium text-right">{{ $comment->user->phone ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-400">Terdaftar</dt>
                        <dd class="text-gray-700 font-medium text-right">{{ $comment->user->created_at?->format('d M Y') }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-400">Status Akun</dt>
                        <dd class="text-right">
                            @if($comment->user->is_active)
                                <span class="text-[11px] text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded font-bold uppercase">Aktif</span>
                            @else
                                <span class="text-[11px] text-gray-600 bg-gray-100 border border-gray-200 px-2.5 py-1 rounded font-bold uppercase">Nonaktif</span>
                            @endif
                        </dd>
                    </div>
                </dl>
                <a href="{{ route('admin.users.edit', $comment->user_id) }}" class="flex items-center justify-center gap-2 mt-5 w-full bg-[#0F767E]/10 hover:bg-[#0F767E]/20 text-[#0F767E] text-sm font-bold px-4 py-2.5 rounded-xl transition-colors">
                    <i class="fa-solid fa-user-pen"></i> Kelola Akun Pengguna
                </a>
            @else
                <p class="text-sm text-gray-400">Akun pengguna sudah dihapus.</p>
            @endif
        </div>

        <!-- Aksi -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h4 class="text-sm font-extrabold text-[#1F2937] mb-4"><i class="fa-solid fa-bolt text-[#F97316] mr-2"></i> Aksi Moderasi</h4>
            <div class="space-y-3">
                <form action="{{ route('admin.comments.toggle', $comment) }}" method="POST">
                    @csrf
                    @if($comment->is_active)
                        <button type="submit" class="w-full bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-700 text-sm font-bold px-4 py-2.5 rounded-xl transition-colors">
                            <i class="fa-solid fa-eye-slash mr-1.5"></i> Sembunyikan Komentar
                        </button>
                    @else
                        <button type="submit" class="w-full bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 text-sm font-bold px-4 py-2.5 rounded-xl transition-colors">
                            <i class="fa-solid fa-eye mr-1.5"></i> Tampilkan Kembali
                        </button>
                    @endif
                </form>
                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus komentar ini secara permanen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 text-sm font-bold px-4 py-2.5 rounded-xl transition-colors">
                        <i class="fa-solid fa-trash mr-1.5"></i> Hapus Komentar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

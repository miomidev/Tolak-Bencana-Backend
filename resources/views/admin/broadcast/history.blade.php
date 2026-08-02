@extends('layouts.admin')

@section('title', 'Riwayat Siaran')
@section('header_title', 'Riwayat Siaran Peringatan')

@section('content')
<div class="space-y-5">

    {{-- Action bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <p class="text-xs text-gray-500">Daftar semua siaran peringatan yang pernah dikirim ke pengguna aplikasi.</p>
        </div>
        <a href="{{ route('admin.broadcast.create') }}"
           class="inline-flex items-center gap-2 bg-[#F97316] hover:bg-[#EA580C] text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors shadow-sm">
            <i class="fa-solid fa-paper-plane"></i> Kirim Siaran Baru
        </a>
    </div>

    {{-- Filter bar --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('admin.broadcast.history') }}" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Tipe Siaran</label>
                <select name="type" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40 bg-white">
                    <option value="">Semua Tipe</option>
                    <option value="emergency_alert" {{ request('type') === 'emergency_alert' ? 'selected' : '' }}>Darurat</option>
                    <option value="flood_alert" {{ request('type') === 'flood_alert' ? 'selected' : '' }}>Banjir</option>
                    <option value="info" {{ request('type') === 'info' ? 'selected' : '' }}>Informasi</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="bg-[#1E3A8A] hover:bg-[#1A2F74] text-white text-sm font-bold px-4 py-2 rounded-xl transition-colors">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                @if(request()->hasAny(['type', 'date_from', 'date_to']))
                    <a href="{{ route('admin.broadcast.history') }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-bold px-4 py-2 rounded-xl transition-colors">
                        <i class="fa-solid fa-xmark mr-1"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h4 class="font-extrabold text-base text-[#1E3A8A]">
                <i class="fa-solid fa-clock-rotate-left mr-2 text-[#0F767E]"></i> Riwayat Siaran
            </h4>
            <span class="text-xs text-gray-400 font-medium">{{ $broadcasts->total() }} siaran ditemukan</span>
        </div>

        @if($broadcasts->isEmpty())
            <div class="py-16 text-center text-gray-400 flex flex-col items-center gap-3">
                <i class="fa-solid fa-bullhorn text-4xl text-gray-300"></i>
                <p class="text-sm font-medium">Belum ada siaran yang dikirim.</p>
                <a href="{{ route('admin.broadcast.create') }}"
                   class="text-[#0F767E] text-xs font-bold hover:underline">Kirim siaran pertama →</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Judul & Isi</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Kirim</th>
                            <th class="text-center px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Penerima</th>
                            <th class="text-center px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Dibaca</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($broadcasts as $broadcast)
                            @php
                                $typeConfig = match($broadcast->type) {
                                    'emergency_alert' => ['label' => 'Darurat', 'icon' => 'fa-triangle-exclamation', 'bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-200'],
                                    'flood_alert'     => ['label' => 'Banjir',  'icon' => 'fa-droplet',             'bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'border' => 'border-orange-200'],
                                    default           => ['label' => 'Info',    'icon' => 'fa-circle-info',         'bg' => 'bg-blue-50',   'text' => 'text-blue-700',   'border' => 'border-blue-200'],
                                };
                                $readPct = $broadcast->recipient_count > 0
                                    ? round(($broadcast->read_count / $broadcast->recipient_count) * 100)
                                    : 0;
                            @endphp
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                {{-- Title & body --}}
                                <td class="px-5 py-4 max-w-xs">
                                    <p class="font-bold text-[#1E293B] text-sm truncate">{{ $broadcast->title }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-2 leading-relaxed">{{ $broadcast->body }}</p>
                                </td>

                                {{-- Type badge --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold border {{ $typeConfig['bg'] }} {{ $typeConfig['text'] }} {{ $typeConfig['border'] }}">
                                        <i class="fa-solid {{ $typeConfig['icon'] }}"></i>
                                        {{ $typeConfig['label'] }}
                                    </span>
                                </td>

                                {{-- Sent at --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-700 font-medium">{{ $broadcast->sent_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ $broadcast->sent_at->format('H:i') }} WIB</p>
                                </td>

                                {{-- Recipient count --}}
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#1E3A8A]/10 text-[#1E3A8A] font-extrabold text-sm">
                                        {{ $broadcast->recipient_count }}
                                    </span>
                                </td>

                                {{-- Read rate --}}
                                <td class="px-4 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-sm font-bold {{ $readPct >= 50 ? 'text-emerald-600' : 'text-gray-500' }}">
                                            {{ $readPct }}%
                                        </span>
                                        <div class="w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $readPct >= 50 ? 'bg-emerald-500' : 'bg-gray-400' }}"
                                                 style="width: {{ $readPct }}%"></div>
                                        </div>
                                        <span class="text-[10px] text-gray-400">{{ $broadcast->read_count }}/{{ $broadcast->recipient_count }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($broadcasts->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $broadcasts->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

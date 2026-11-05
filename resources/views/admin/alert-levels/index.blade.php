@extends('layouts.admin')

@section('title', 'Level Siaga')
@section('header_title', 'Level Siaga')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-extrabold text-[#1F2937]">Daftar Level Siaga</h3>
        <p class="text-sm text-gray-400">Ambang tinggi air (cm) untuk penentuan status siaga bencana</p>
    </div>
    <a href="{{ route('admin.alert-levels.create') }}" class="bg-[#15803D] hover:bg-[#0a5258] text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Level
    </a>
</div>

<!-- Visual scale -->
@if($levels->isNotEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 mb-6">
    <h4 class="text-sm font-extrabold text-[#1F2937] mb-4"><i class="fa-solid fa-gauge-high text-[#15803D] mr-2"></i> Skala Level Siaga</h4>
    <div class="flex h-4 rounded-full overflow-hidden">
        @foreach($levels as $level)
            @php
                $width = 100 / max($levels->count(), 1);
            @endphp
            <div class="flex items-center justify-center text-white" style="width: {{ $width }}%; background-color: {{ $level->color }};" title="{{ $level->name }} ({{ $level->min_height }} - {{ $level->max_height ?? '∞' }} cm)">
                @if($width >= 20)<span class="text-[10px] font-black drop-shadow">{{ $level->name }}</span>@endif
            </div>
        @endforeach
    </div>
    <div class="flex justify-between text-[10px] text-gray-400 mt-2">
        @foreach($levels as $level)
            <span>{{ $level->min_height }}{{ $level->max_height ? '–' . $level->max_height : '+' }} cm</span>
        @endforeach
    </div>
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <th class="px-5 py-3.5 w-16">#</th>
                    <th class="px-5 py-3.5">Nama Level</th>
                    <th class="px-5 py-3.5">Warna</th>
                    <th class="px-5 py-3.5">Rentang Tinggi (cm)</th>
                    <th class="px-5 py-3.5">Status</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($levels as $level)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4 text-gray-400">{{ $levels->firstItem() + $loop->index }}</td>
                        <td class="px-5 py-4 font-bold text-[#1F2937]">{{ $level->name }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-2 text-xs text-gray-500">
                                <span class="w-6 h-6 rounded-lg border border-gray-200 shadow-sm" style="background-color: {{ $level->color }}"></span>
                                {{ $level->color }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-gray-600">
                            {{ $level->min_height }} – {{ $level->max_height ?? '∞ (lebih)' }} cm
                        </td>
                        <td class="px-5 py-4">
                            <form action="{{ route('admin.alert-levels.toggle', $level) }}" method="POST">
                                @csrf
                                <button type="submit" class="relative inline-flex items-center h-6 w-11 rounded-full transition-colors {{ $level->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}">
                                    <span class="inline-block w-4 h-4 transform bg-white rounded-full shadow transition-transform {{ $level->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </form>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.alert-levels.edit', $level) }}" class="text-[#15803D] hover:bg-teal-50 p-2 rounded-lg transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.alert-levels.destroy', $level) }}" method="POST" onsubmit="return confirm('Hapus level siaga ini?')">
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
                            <i class="fa-solid fa-gauge-high text-3xl block mb-2 text-gray-300"></i>
                            Belum ada level siaga. <a href="{{ route('admin.alert-levels.create') }}" class="text-[#15803D] font-bold">Tambah sekarang</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $levels->links() }}
    </div>
</div>
@endsection

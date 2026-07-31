@extends('layouts.admin')

@section('title', 'Shelter Evakuasi')
@section('header_title', 'Shelter Evakuasi')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-extrabold text-[#1F2937]">Daftar Shelter</h3>
        <p class="text-sm text-gray-400">Kelola titik pengungsian evakuasi banjir</p>
    </div>
    <a href="{{ route('admin.shelters.create') }}" class="bg-[#0F767E] hover:bg-[#0a5258] text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Shelter
    </a>
</div>

<!-- Filter bar -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6">
    <form method="GET" action="{{ route('admin.shelters.index') }}" class="flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[220px]">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / alamat..." class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
        </div>
        <select name="status" class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
            <option value="">Semua Status</option>
            <option value="active" @selected(request('status') === 'active')>Aktif</option>
            <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
            <option value="full" @selected(request('status') === 'full')>Penuh</option>
        </select>
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold px-4 py-2.5 rounded-xl transition-colors">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
        @if(request('q') || request('status'))
            <a href="{{ route('admin.shelters.index') }}" class="text-sm text-gray-400 hover:text-gray-600"><i class="fa-solid fa-rotate-left"></i> Reset</a>
        @endif
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <th class="px-5 py-3.5">Nama</th>
                    <th class="px-5 py-3.5">Alamat</th>
                    <th class="px-5 py-3.5">Kapasitas</th>
                    <th class="px-5 py-3.5">Status</th>
                    <th class="px-5 py-3.5">Kontak</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($shelters as $shelter)
                    @php $ss = \App\Support\UiHelper::shelterStatus($shelter->status); $pct = $shelter->capacity > 0 ? round(($shelter->current_occupancy / $shelter->capacity) * 100) : 0; @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4">
                            <p class="font-bold text-[#1F2937]">{{ $shelter->name }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">{{ $shelter->latitude }}, {{ $shelter->longitude }}</p>
                        </td>
                        <td class="px-5 py-4 text-gray-500 max-w-[260px]">{{ Str::limit($shelter->address, 60) }}</td>
                        <td class="px-5 py-4">
                            <span class="font-bold text-[#1F2937]">{{ $shelter->current_occupancy }}/{{ $shelter->capacity }}</span>
                            <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden mt-1">
                                <div class="h-full rounded-full {{ $pct >= 80 ? 'bg-red-500' : ($pct >= 50 ? 'bg-amber-400' : 'bg-emerald-500') }}" style="width: {{ min($pct, 100) }}%"></div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-[11px] {{ $ss['text'] }} {{ $ss['bg'] }} border {{ $ss['border'] }} px-2.5 py-1 rounded font-bold uppercase">{{ $ss['label'] }}</span>
                        </td>
                        <td class="px-5 py-4 text-gray-500">{{ $shelter->contact ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.shelters.edit', $shelter) }}" class="text-[#0F767E] hover:bg-teal-50 p-2 rounded-lg transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.shelters.destroy', $shelter) }}" method="POST" onsubmit="return confirm('Hapus shelter ini?')">
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
                            <i class="fa-solid fa-house-chimney-medical text-3xl block mb-2 text-gray-300"></i>
                            Belum ada shelter. <a href="{{ route('admin.shelters.create') }}" class="text-[#0F767E] font-bold">Tambah sekarang</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $shelters->links() }}
    </div>
</div>
@endsection

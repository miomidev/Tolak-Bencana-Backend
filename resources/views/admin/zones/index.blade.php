@extends('layouts.admin')

@section('title', 'Zona Rawan Banjir')
@section('header_title', 'Zona Rawan Banjir')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-extrabold text-[#1F2937]">Daftar Zona Rawan Banjir</h3>
        <p class="text-sm text-gray-400">Area rawan banjir yang dimonitor di wilayah KALIWEDI</p>
    </div>
    <a href="{{ route('admin.zones.create') }}" class="bg-[#0F767E] hover:bg-[#0a5258] text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Zona
    </a>
</div>

<!-- Filter bar -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6">
    <form method="GET" action="{{ route('admin.zones.index') }}" class="flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[220px]">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama zona..." class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
        </div>
        <select name="risk" class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
            <option value="">Semua Risiko</option>
            <option value="low" @selected(request('risk') === 'low')>Rendah</option>
            <option value="medium" @selected(request('risk') === 'medium')>Sedang</option>
            <option value="high" @selected(request('risk') === 'high')>Tinggi</option>
            <option value="critical" @selected(request('risk') === 'critical')>Kritis</option>
        </select>
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold px-4 py-2.5 rounded-xl transition-colors">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
        @if(request('q') || request('risk'))
            <a href="{{ route('admin.zones.index') }}" class="text-sm text-gray-400 hover:text-gray-600"><i class="fa-solid fa-rotate-left"></i> Reset</a>
        @endif
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <th class="px-5 py-3.5">Nama Zona</th>
                    <th class="px-5 py-3.5">Tingkat Risiko</th>
                    <th class="px-5 py-3.5">Deskripsi</th>
                    <th class="px-5 py-3.5">Dibuat</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($zones as $zone)
                    @php $risk = \App\Support\UiHelper::risk($zone->risk_level); @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4 font-bold text-[#1F2937]">{{ $zone->name }}</td>
                        <td class="px-5 py-4">
                            <span class="text-[11px] {{ $risk['text'] }} {{ $risk['bg'] }} border {{ $risk['border'] }} px-2.5 py-1 rounded font-bold uppercase">{{ $risk['label'] }}</span>
                        </td>
                        <td class="px-5 py-4 text-gray-500 max-w-[300px]">{{ Str::limit($zone->description, 70) ?? '-' }}</td>
                        <td class="px-5 py-4 text-gray-400 text-xs">{{ $zone->created_at?->format('d M Y') }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.zones.edit', $zone) }}" class="text-[#0F767E] hover:bg-teal-50 p-2 rounded-lg transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.zones.destroy', $zone) }}" method="POST" onsubmit="return confirm('Hapus zona ini?')">
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
                        <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-circle-exclamation text-3xl block mb-2 text-gray-300"></i>
                            Belum ada zona rawan banjir. <a href="{{ route('admin.zones.create') }}" class="text-[#0F767E] font-bold">Tambah sekarang</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $zones->links() }}
    </div>
</div>
@endsection

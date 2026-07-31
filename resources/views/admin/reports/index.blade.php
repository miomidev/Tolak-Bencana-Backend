@extends('layouts.admin')

@section('title', 'Laporan Banjir')
@section('header_title', 'Moderasi Laporan Banjir')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-extrabold text-[#1F2937]">Daftar Laporan</h3>
        <p class="text-sm text-gray-400">Verifikasi laporan banjir yang masuk dari warga</p>
    </div>
    <a href="{{ route('admin.reports.export', request()->only(['status', 'from', 'to'])) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold px-4 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-2">
        <i class="fa-solid fa-file-excel text-[#0F767E]"></i> Export Excel
    </a>
</div>

<!-- Filter bar -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari alamat / deskripsi..." class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
        </div>
        <select name="status" class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
            <option value="">Semua Status</option>
            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
            <option value="verified" @selected(request('status') === 'verified')>Terverifikasi</option>
            <option value="resolved" @selected(request('status') === 'resolved')>Selesai</option>
            <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
        </select>
        <select name="severity" class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
            <option value="">Semua Severity</option>
            <option value="low" @selected(request('severity') === 'low')>Rendah</option>
            <option value="medium" @selected(request('severity') === 'medium')>Waspada</option>
            <option value="high" @selected(request('severity') === 'high')>Siaga</option>
            <option value="critical" @selected(request('severity') === 'critical')>Kritis</option>
        </select>
        <select name="category" class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
            <option value="">Semua Kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ request('from') }}" class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40" title="Dari tanggal">
        <input type="date" name="to" value="{{ request('to') }}" class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40" title="Sampai tanggal">
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold px-4 py-2.5 rounded-xl transition-colors">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
        @if(request()->anyFilled(['q', 'status', 'severity', 'category', 'from', 'to']))
            <a href="{{ route('admin.reports.index') }}" class="text-sm text-gray-400 hover:text-gray-600"><i class="fa-solid fa-rotate-left"></i> Reset</a>
        @endif
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <th class="px-5 py-3.5 w-16">Foto</th>
                    <th class="px-5 py-3.5">Pelapor</th>
                    <th class="px-5 py-3.5">Lokasi</th>
                    <th class="px-5 py-3.5">Kategori</th>
                    <th class="px-5 py-3.5">Severity</th>
                    <th class="px-5 py-3.5">Status</th>
                    <th class="px-5 py-3.5">Waktu</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reports as $report)
                    @php $sev = \App\Support\UiHelper::severity($report->severity); $st = \App\Support\UiHelper::status($report->status); @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4">
                            @if($report->photo_url)
                                <img src="{{ \Illuminate\Support\Str::startsWith($report->photo_url, 'http') ? $report->photo_url : asset($report->photo_url) }}" class="w-12 h-12 rounded-lg object-cover border border-gray-200" alt="">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400"><i class="fa-solid fa-image"></i></div>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-bold text-[#1F2937]">{{ $report->user?->name ?? 'Pengguna' }}</p>
                            <p class="text-[11px] text-gray-400">{{ $report->comments_count }} komentar · {{ $report->likes_count }} like</p>
                        </td>
                        <td class="px-5 py-4 text-gray-500 max-w-[220px]">
                            <p class="truncate">{{ $report->address }}</p>
                            <p class="text-[11px] text-gray-400 truncate">{{ Str::limit($report->description, 50) }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-[11px] bg-gray-100 text-gray-600 px-2.5 py-1 rounded font-bold">{{ $report->category ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-[11px] {{ $sev['text'] }} {{ $sev['bg'] }} border {{ $sev['border'] }} px-2.5 py-1 rounded font-bold uppercase">{{ $sev['label'] }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-[11px] {{ $st['text'] }} {{ $st['bg'] }} border {{ $st['border'] }} px-2.5 py-1 rounded font-bold uppercase">{{ $st['label'] }}</span>
                        </td>
                        <td class="px-5 py-4 text-gray-400 text-xs">{{ $report->reported_at?->format('d M Y H:i') }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.reports.show', $report) }}" class="bg-[#0F767E] hover:bg-[#0a5258] text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors">
                                    Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-file-waveform text-3xl block mb-2 text-gray-300"></i>
                            Tidak ada laporan yang cocok dengan filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $reports->links() }}
    </div>
</div>
@endsection

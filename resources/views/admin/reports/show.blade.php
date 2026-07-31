@extends('layouts.admin')

@section('title', 'Detail Laporan')
@section('header_title', 'Detail Laporan')

@php
    $sev = \App\Support\UiHelper::severity($report->severity);
    $st = \App\Support\UiHelper::status($report->status);
    $photo = $report->photo_url && !\Illuminate\Support\Str::startsWith($report->photo_url, 'http') ? asset($report->photo_url) : $report->photo_url;
@endphp

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.reports.index', request()->except('page')) }}" class="text-sm text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke daftar</a>
    <a href="{{ route('admin.reports.export', ['status' => $report->status]) }}" class="text-sm text-gray-400 hover:text-gray-600"><i class="fa-solid fa-file-excel mr-1 text-[#0F767E]"></i> Export Excel</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Photo + actions -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            @if($photo)
                <img src="{{ $photo }}" class="w-full h-64 object-cover" alt="Foto laporan">
            @else
                <div class="w-full h-64 bg-gray-100 flex items-center justify-center text-gray-400">
                    <div class="text-center">
                        <i class="fa-solid fa-image text-4xl block mb-2"></i>
                        <span class="text-sm">Tidak ada foto</span>
                    </div>
                </div>
            @endif
            <div class="p-5">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-[11px] {{ $sev['text'] }} {{ $sev['bg'] }} border {{ $sev['border'] }} px-2.5 py-1 rounded font-bold uppercase">{{ $sev['label'] }}</span>
                    <span class="text-[11px] {{ $st['text'] }} {{ $st['bg'] }} border {{ $st['border'] }} px-2.5 py-1 rounded font-bold uppercase">{{ $st['label'] }}</span>
                    @if($report->category)
                        <span class="text-[11px] bg-gray-100 text-gray-600 px-2.5 py-1 rounded font-bold">{{ $report->category }}</span>
                    @endif
                </div>

                <h4 class="text-sm font-extrabold text-[#1F2937] mb-2">Aksi Moderasi</h4>
                <div class="flex flex-col gap-2">
                    <form action="{{ route('admin.reports.status', $report) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="verified">
                        <button type="submit" class="w-full bg-[#0F767E] hover:bg-[#0a5258] text-white text-sm font-bold px-4 py-2.5 rounded-xl transition-colors">
                            <i class="fa-solid fa-check mr-1.5"></i> Validasi (Terverifikasi)
                        </button>
                    </form>
                    <form action="{{ route('admin.reports.status', $report) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="resolved">
                        <button type="submit" class="w-full bg-emerald-100 hover:bg-emerald-200 text-emerald-700 text-sm font-bold px-4 py-2.5 rounded-xl transition-colors">
                            <i class="fa-solid fa-flag-checkered mr-1.5"></i> Tandai Selesai
                        </button>
                    </form>
                    <form action="{{ route('admin.reports.status', $report) }}" method="POST" onsubmit="return confirm('Tolak laporan ini?')">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="w-full bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 text-sm font-bold px-4 py-2.5 rounded-xl transition-colors">
                            <i class="fa-solid fa-xmark mr-1.5"></i> Tolak Laporan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h4 class="text-sm font-extrabold text-[#1F2937] mb-4"><i class="fa-solid fa-circle-info text-[#0F767E] mr-2"></i> Informasi Laporan</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Pelapor</p>
                    <p class="font-bold text-[#1F2937]">{{ $report->user?->name ?? '-' }}</p>
                    <p class="text-xs text-gray-400">{{ $report->user?->username }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Kontak Pelapor</p>
                    <p class="text-gray-600">{{ $report->user?->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Waktu Dilaporkan</p>
                    <p class="text-gray-600">{{ $report->reported_at?->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Koordinat</p>
                    <p class="text-gray-600">{{ $report->latitude }}, {{ $report->longitude }}</p>
                </div>
            </div>

            <div class="mt-5 pt-5 border-t border-gray-100">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Alamat</p>
                <p class="text-gray-700 mb-4">{{ $report->address }}</p>
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Deskripsi</p>
                <p class="text-gray-700">{{ $report->description }}</p>
            </div>

            <div class="mt-5 pt-5 border-t border-gray-100 flex gap-6 text-sm">
                <span class="text-gray-500"><i class="fa-solid fa-thumbs-up mr-1.5 text-[#0F767E]"></i>{{ $report->likes_count }} suka</span>
                <span class="text-gray-500"><i class="fa-solid fa-comment mr-1.5 text-[#0F767E]"></i>{{ $report->comments_count }} komentar</span>
            </div>
        </div>

        <!-- Map -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50">
                <h4 class="text-sm font-extrabold text-[#1F2937]"><i class="fa-solid fa-map-location-dot text-[#0F767E] mr-2"></i> Lokasi Kejadian</h4>
            </div>
            <div id="report-map" class="w-full h-72 z-0"></div>
        </div>

        <!-- Comments -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h4 class="text-sm font-extrabold text-[#1F2937] mb-4"><i class="fa-solid fa-comment text-[#0F767E] mr-2"></i> Komentar ({{ $report->comments_count }})</h4>
            <div class="space-y-4">
                @forelse($report->comments as $comment)
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 text-xs font-bold shrink-0">{{ strtoupper(substr($comment->user?->name ?? '?', 0, 1)) }}</div>
                        <div class="flex-1">
                            <p class="text-xs"><span class="font-bold text-[#1F2937]">{{ $comment->user?->name ?? 'Pengguna' }}</span> <span class="text-gray-400">· {{ $comment->created_at?->diffForHumans() }}</span></p>
                            <p class="text-sm text-gray-600 mt-0.5">{{ $comment->comment }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada komentar.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map = L.map('report-map').setView([{{ $report->latitude }}, {{ $report->longitude }}], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const sevHex = @json($sev['hex']);
        L.marker([{{ $report->latitude }}, {{ $report->longitude }}], {
            icon: L.divIcon({
                className: 'custom-div-icon',
                html: "<div class='w-7 h-7 rounded-full flex items-center justify-center text-white border-2 border-white shadow-lg' style='background-color: " + sevHex + "'><i class='fa-solid fa-triangle-exclamation text-[11px]'></i></div>",
                iconSize: [28, 28],
                iconAnchor: [14, 14]
            })
        }).addTo(map).bindPopup("<b>" + @json($report->address) + "</b>");
    });
</script>
@endsection

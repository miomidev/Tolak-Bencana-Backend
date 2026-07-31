@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard Pemantauan Banjir')

@section('content')
<!-- Welcome banner -->
<div class="mb-8 p-6 bg-gradient-to-r from-[#1E3A8A] to-[#0F767E] text-white rounded-2xl shadow-md relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-2xl -translate-y-12 translate-x-12"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-extrabold tracking-tight">Halo, {{ Auth::user()->name }}!</h3>
            <p class="text-white/80 text-sm mt-1">Selamat datang di Panel Administrator KALIWEDI. Pantau laporan banjir rob warga secara real-time.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="location.reload()" class="bg-white/10 hover:bg-white/20 border border-white/20 px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                <i class="fa-solid fa-arrows-rotate"></i> Refresh Data
            </button>
        </div>
    </div>
</div>

<!-- Stats grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat 1: Laporan Hari Ini -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex items-center justify-between">
        <div class="flex flex-col gap-1">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Laporan Hari Ini</span>
            <span class="text-3xl font-black text-[#1F2937]">{{ number_format($stats['reports_today']) }}</span>
            <span class="text-[10px] {{ $stats['reports_today_pct'] >= 0 ? 'text-emerald-600' : 'text-red-600' }} font-semibold flex items-center gap-1 mt-1">
                <i class="fa-solid fa-{{ $stats['reports_today_pct'] >= 0 ? 'arrow-trend-up' : 'arrow-trend-down' }}"></i>
                {{ $stats['reports_today_pct'] >= 0 ? '+' : '' }}{{ $stats['reports_today_pct'] }}% dari kemarin
            </span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#1E3A8A] flex items-center justify-center text-xl shadow-inner font-bold">
            <i class="fa-solid fa-file-waveform"></i>
        </div>
    </div>

    <!-- Stat 2: Laporan Kritis -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex items-center justify-between">
        <div class="flex flex-col gap-1">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Laporan Kritis (Belum Selesai)</span>
            <span class="text-3xl font-black text-[#DC2626]">{{ number_format($stats['critical']) }}</span>
            <span class="text-[10px] text-[#DC2626] font-semibold flex items-center gap-1 mt-1">
                <i class="fa-solid fa-circle-exclamation"></i> Butuh penanganan segera
            </span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-rose-50 text-[#DC2626] flex items-center justify-center text-xl shadow-inner font-bold">
            <i class="fa-solid fa-droplet"></i>
        </div>
    </div>

    <!-- Stat 3: Laporan Pending -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex items-center justify-between">
        <div class="flex flex-col gap-1">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Laporan Pending</span>
            <span class="text-3xl font-black text-[#F97316]">{{ number_format($stats['pending']) }}</span>
            <span class="text-[10px] text-gray-400 font-semibold flex items-center gap-1 mt-1">
                <i class="fa-solid fa-clock"></i> Butuh verifikasi cepat
            </span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-[#F97316] flex items-center justify-center text-xl shadow-inner font-bold">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
    </div>

    <!-- Stat 4: Shelter Aktif -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex items-center justify-between">
        <div class="flex flex-col gap-1">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Shelter Aktif</span>
            <span class="text-3xl font-black text-[#0F767E]">{{ $stats['shelter_active'] }} / {{ $stats['shelter_total'] }}</span>
            <span class="text-[10px] text-[#0F767E] font-semibold flex items-center gap-1 mt-1">
                <i class="fa-solid fa-shield-halved"></i> Daya tampung {{ $stats['shelter_occupancy_pct'] }}%
            </span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-teal-50 text-[#0F767E] flex items-center justify-center text-xl shadow-inner font-bold">
            <i class="fa-solid fa-house-chimney-medical"></i>
        </div>
    </div>
</div>

<!-- Chart + Latest Reports -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
    <!-- Trend chart -->
    <div class="lg:col-span-5 flex flex-col bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-150 bg-gray-50">
            <h4 class="font-extrabold text-base text-[#1E3A8A]"><i class="fa-solid fa-chart-column mr-2"></i> Tren Laporan 7 Hari Terakhir</h4>
            <p class="text-[11px] text-gray-400 font-normal">Jumlah laporan masuk per hari</p>
        </div>
        <div class="p-5 flex-1 min-h-[300px]">
            <canvas id="trend-chart"></canvas>
        </div>
    </div>

    <!-- Latest reports column -->
    <div class="lg:col-span-7 flex flex-col bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-150 bg-gray-50 flex items-center justify-between">
            <div>
                <h4 class="font-extrabold text-base text-[#1E3A8A]"><i class="fa-solid fa-clock-rotate-left mr-2"></i> Aktivitas Laporan Terbaru</h4>
                <p class="text-[11px] text-gray-400 font-normal">Laporan masuk dari warga yang butuh perhatian</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="text-xs font-bold text-[#0F767E] hover:text-[#0a5258] flex items-center gap-1">
                Lihat Semua <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="flex-1 overflow-y-auto divide-y divide-gray-150 max-h-[450px]">
            @forelse($latestReports as $report)
                @php $sev = \App\Support\UiHelper::severity($report->severity); $st = \App\Support\UiHelper::status($report->status); @endphp
                <a href="{{ route('admin.reports.show', $report) }}" class="p-5 hover:bg-gray-50 transition-colors flex flex-col gap-2 block">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-[#1E3A8A]">{{ $report->user?->name ?? 'Pengguna' }}</span>
                        <span class="text-[10px] {{ $st['text'] }} {{ $st['bg'] }} border {{ $st['border'] }} px-2 py-0.5 rounded font-bold uppercase">{{ $st['label'] }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-500">
                        <i class="fa-solid fa-location-dot"></i>
                        <span class="truncate">{{ $report->address }}</span>
                    </div>
                    <p class="text-xs text-gray-400 font-normal mt-0.5 italic line-clamp-2">"{{ Str::limit($report->description, 120) }}"</p>
                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] {{ $sev['text'] }} {{ $sev['bg'] }} border {{ $sev['border'] }} px-2 py-0.5 rounded font-bold uppercase">{{ $sev['label'] }}</span>
                            <span class="text-[10px] text-gray-400"><i class="fa-regular fa-clock"></i> {{ $report->reported_at?->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-10 text-center text-sm text-gray-400">
                    <i class="fa-solid fa-inbox text-3xl block mb-2 text-gray-300"></i>
                    Belum ada laporan masuk.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Map -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <div class="lg:col-span-8 flex flex-col bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-150 flex justify-between items-center bg-gray-50">
            <div>
                <h4 class="font-extrabold text-base text-[#1E3A8A]"><i class="fa-solid fa-map-location-dot mr-2"></i> Peta Sebaran Laporan & Evakuasi</h4>
                <p class="text-[11px] text-gray-400 font-normal">Kecamatan rawan banjir Indramayu termonitor real-time</p>
            </div>
        </div>
        <div class="flex-1 min-h-[450px] relative" id="dashboard-map">
            <div class="absolute inset-0 bg-white/80 z-40 flex items-center justify-center font-semibold text-[#1E3A8A]" id="map-loader">
                <i class="fa-solid fa-spinner animate-spin text-2xl mr-3"></i> Memuat Peta Pemantauan...
            </div>
            <div class="absolute bottom-3 right-3 z-30 bg-white/95 backdrop-blur rounded-xl shadow-md border border-gray-200 px-3 py-2.5 text-[10px] space-y-1.5">
                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-red-600"></span><span class="text-gray-600 font-semibold">Kritis / Tinggi</span></div>
                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span><span class="text-gray-600 font-semibold">Sedang</span></div>
                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span><span class="text-gray-600 font-semibold">Rendah</span></div>
                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span><span class="text-gray-600 font-semibold">Terverifikasi</span></div>
                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-amber-400 border border-dashed border-gray-400"></span><span class="text-gray-600 font-semibold">Menunggu Verifikasi</span></div>
                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 border border-emerald-700"></span><span class="text-gray-600 font-semibold">Selesai / Surut</span></div>
            </div>
        </div>
    </div>

    <!-- Shelter list -->
    <div class="lg:col-span-4 flex flex-col bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-150 bg-gray-50 flex items-center justify-between">
            <div>
                <h4 class="font-extrabold text-base text-[#0F767E]"><i class="fa-solid fa-house-chimney-medical mr-2"></i> Shelter Evakuasi</h4>
                <p class="text-[11px] text-gray-400 font-normal">Titik pengungsian aktif</p>
            </div>
            <a href="{{ route('admin.shelters.index') }}" class="text-xs font-bold text-[#0F767E] hover:text-[#0a5258]">Kelola</a>
        </div>
        <div class="flex-1 overflow-y-auto divide-y divide-gray-150 max-h-[450px]">
            @forelse($shelters as $shelter)
                @php $ss = \App\Support\UiHelper::shelterStatus($shelter->status); $pct = $shelter->capacity > 0 ? round(($shelter->current_occupancy / $shelter->capacity) * 100) : 0; @endphp
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-[#1F2937] truncate">{{ $shelter->name }}</span>
                        <span class="text-[10px] {{ $ss['text'] }} {{ $ss['bg'] }} border {{ $ss['border'] }} px-2 py-0.5 rounded font-bold uppercase shrink-0">{{ $ss['label'] }}</span>
                    </div>
                    <div class="mt-2">
                        <div class="flex justify-between text-[10px] text-gray-400 mb-1">
                            <span>Terisi {{ $shelter->current_occupancy }}/{{ $shelter->capacity }} orang</span>
                            <span>{{ $pct }}%</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $pct >= 80 ? 'bg-red-500' : ($pct >= 50 ? 'bg-amber-400' : 'bg-emerald-500') }}" style="width: {{ min($pct, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-sm text-gray-400">Belum ada shelter terdaftar.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mapLoader = document.getElementById('map-loader');
        if (mapLoader) mapLoader.remove();

        const indramayuBounds = L.latLngBounds(
            L.latLng(-6.55, 107.85),
            L.latLng(-6.05, 108.65)
        );

        const map = L.map('dashboard-map', {
            center: [-6.27, 108.20],
            zoom: 11,
            minZoom: 10,
            maxZoom: 18,
            maxBounds: indramayuBounds,
            maxBoundsViscosity: 1.0
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const reportIcons = {
            low: L.divIcon({ className: 'custom-div-icon', html: "<div class='w-6 h-6 rounded-full bg-emerald-500 border-2 border-white shadow-lg flex items-center justify-center text-white'><i class='fa-solid fa-check text-[10px]'></i></div>", iconSize: [24, 24], iconAnchor: [12, 12] }),
            medium: L.divIcon({ className: 'custom-div-icon', html: "<div class='w-6 h-6 rounded-full bg-amber-400 border-2 border-white shadow-lg flex items-center justify-center text-white'><i class='fa-solid fa-triangle-exclamation text-[10px]'></i></div>", iconSize: [24, 24], iconAnchor: [12, 12] }),
            high: L.divIcon({ className: 'custom-div-icon', html: "<div class='w-6 h-6 rounded-full bg-orange-500 border-2 border-white shadow-lg flex items-center justify-center text-white'><i class='fa-solid fa-triangle-exclamation text-[10px]'></i></div>", iconSize: [24, 24], iconAnchor: [12, 12] }),
            critical: L.divIcon({ className: 'custom-div-icon', html: "<div class='w-6 h-6 rounded-full bg-red-600 border-2 border-white shadow-lg flex items-center justify-center text-white'><i class='fa-solid fa-triangle-exclamation text-[10px] animate-pulse'></i></div>", iconSize: [24, 24], iconAnchor: [12, 12] }),
        };

        const statusMeta = {
            pending:  { label: 'MENUNGGU VERIFIKASI', cls: 'bg-amber-100 text-amber-700' },
            verified: { label: 'TERVERIFIKASI',       cls: 'bg-blue-100 text-blue-700' },
            resolved: { label: 'SELESAI / SURUT',     cls: 'bg-emerald-100 text-emerald-700' },
        };

        const statusBadge = {
            pending:  "<span class='absolute -bottom-1 -right-1 w-3.5 h-3.5 rounded-full bg-amber-400 border-2 border-white'></span>",
            verified: "<span class='absolute -bottom-1 -right-1 w-3.5 h-3.5 rounded-full bg-blue-500 border-2 border-white flex items-center justify-center text-white'><i class='fa-solid fa-check text-[6px]'></i></span>",
            resolved: "<span class='absolute -bottom-1 -right-1 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white'></span>",
        };

        function makeReportIcon(severity, status) {
            const base = reportIcons[severity] || reportIcons.medium;
            const badge = statusBadge[status] || '';
            const colors = { low: 'bg-emerald-500', medium: 'bg-amber-400', high: 'bg-orange-500', critical: 'bg-red-600' };
            const icon = (colors[severity] || 'bg-amber-400') === 'bg-emerald-500'
                ? 'fa-solid fa-check text-[10px]'
                : 'fa-solid fa-triangle-exclamation text-[10px]';
            return L.divIcon({
                className: 'custom-div-icon',
                html: "<div class='relative w-6 h-6 rounded-full " + (colors[severity] || 'bg-amber-400') + " border-2 border-white shadow-lg flex items-center justify-center text-white'><i class='" + icon + (severity === 'critical' ? ' animate-pulse' : '') + "'></i>" + badge + "</div>",
                iconSize: [24, 24],
                iconAnchor: [12, 12],
                popupAnchor: [0, -12],
            });
        }

        const shelterIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div class='w-7 h-7 rounded-full bg-[#0F767E] border-2 border-white shadow-lg flex items-center justify-center text-white'><i class='fa-solid fa-house-chimney-medical text-[11px]'></i></div>",
            iconSize: [28, 28],
            iconAnchor: [14, 14]
        });

        @php $mapReports = App\Models\Report::where('status', '!=', 'rejected')->get(['id', 'latitude', 'longitude', 'severity', 'status', 'description', 'address']); @endphp

        const reports = @json($mapReports);
        reports.forEach(function (r) {
            const sm = statusMeta[r.status] || statusMeta.pending;
            L.marker([parseFloat(r.latitude), parseFloat(r.longitude)], { icon: makeReportIcon(r.severity, r.status) })
                .addTo(map)
                .bindPopup("<div class='p-2 w-52'><span class='inline-block text-[9px] font-bold px-1.5 py-0.5 rounded mb-1 " + sm.cls + "'>" + sm.label + "</span><span class='font-bold text-sm block'>" + r.address + "</span><span class='text-xs text-gray-400 block mt-1'>" + (r.description || '').substring(0, 80) + "</span></div>");
        });

        const shelters = @json($shelters);
        shelters.forEach(function (s) {
            L.marker([parseFloat(s.latitude), parseFloat(s.longitude)], { icon: shelterIcon })
                .addTo(map)
                .bindPopup("<div class='p-1.5 w-48'><span class='font-bold text-sm block text-[#0F767E]'>" + s.name + "</span><span class='text-xs text-gray-500 block mt-1'>" + s.current_occupancy + "/" + s.capacity + " orang</span></div>");
        });

        // Trend chart
        const ctx = document.getElementById('trend-chart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartDates),
                    datasets: [{
                        label: 'Laporan',
                        data: @json($chartTotals),
                        backgroundColor: '#0F767E',
                        borderRadius: 6,
                        maxBarThickness: 36
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection

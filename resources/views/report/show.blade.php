<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Detail laporan bencana TOLAK BENCANA">
    <title>Laporan Bencana | TOLAK BENCANA</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        display: ['Fredoka', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        h1, h2, h3 { font-family: 'Fredoka', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased flex flex-col">
    @php
        $severity = strtolower($report->severity ?? 'low');
        $badge = match ($severity) {
            'critical' => ['bg-red-600', 'Siaga'],
            'high' => ['bg-orange-500', 'Waspada'],
            'medium' => ['bg-amber-500', 'Waspada'],
            default => ['bg-green-500', 'Aman'],
        };
        $isResolved = in_array(strtolower($report->status ?? ''), ['verified', 'resolved']);
    @endphp

    <!-- Header -->
    <header class="bg-teal-700 text-white">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="TOLAK BENCANA" class="h-10 w-10 rounded-full bg-white p-0.5 object-contain">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-teal-100">Pelaporan Banjir</p>
                <h1 class="text-lg font-display font-bold leading-tight">TOLAK BENCANA</h1>
            </div>
            <div class="ml-auto text-right">
                <span class="text-[10px] uppercase tracking-wide text-teal-100">Kode Laporan</span>
                <p class="text-sm font-bold">LP-2026-{{ strtoupper(substr($report->id, 0, 3)) }}</p>
            </div>
        </div>
    </header>

    <main class="flex-1 w-full max-w-3xl mx-auto px-4 py-6">
        <!-- Photo -->
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm mb-4">
            @if ($report->photo_url)
                <img src="{{ $report->photo_url }}" alt="Foto lokasi banjir" class="w-full h-56 object-cover">
            @else
                <div class="w-full h-40 bg-gradient-to-br from-teal-600 to-blue-800 flex items-center justify-center">
                    <span class="text-white/80 text-sm font-semibold">Foto belum tersedia</span>
                </div>
            @endif
        </div>

        <!-- Badge -->
        <div class="mb-4">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-white text-xs font-bold {{ $badge[0] }}">
                <i class="fas fa-water"></i> STATUS: {{ $badge[1] }}
            </span>
        </div>

        <!-- Description -->
        <div class="bg-white rounded-2xl p-5 shadow-sm mb-4">
            <h2 class="text-base font-display font-bold text-slate-900 mb-2">Deskripsi Laporan</h2>
            <p class="text-sm text-slate-700 leading-relaxed">{{ $report->description }}</p>

            <div class="h-px bg-slate-100 my-4"></div>

            <dl class="space-y-3 text-sm">
                <div class="flex items-start gap-3">
                    <dt class="w-32 shrink-0 text-slate-500 font-medium">Lokasi</dt>
                    <dd class="text-slate-800 font-semibold flex-1">{{ $report->address }}</dd>
                </div>
                <div class="flex items-start gap-3">
                    <dt class="w-32 shrink-0 text-slate-500 font-medium">Kategori</dt>
                    <dd class="text-slate-800 font-semibold">{{ $report->category ?? 'Bencana' }}</dd>
                </div>
                <div class="flex items-start gap-3">
                    <dt class="w-32 shrink-0 text-slate-500 font-medium">Koordinat</dt>
                    <dd class="text-slate-800 font-semibold">{{ $report->latitude }}, {{ $report->longitude }}</dd>
                </div>
                <div class="flex items-start gap-3">
                    <dt class="w-32 shrink-0 text-slate-500 font-medium">Tanggal</dt>
                    <dd class="text-slate-800 font-semibold">{{ \Carbon\Carbon::parse($report->reported_at)->locale('id')->translatedFormat('d F Y') }}</dd>
                </div>
                <div class="flex items-start gap-3">
                    <dt class="w-32 shrink-0 text-slate-500 font-medium">Status</dt>
                    <dd class="font-semibold {{ $isResolved ? 'text-green-600' : 'text-orange-600' }}">{{ $isResolved ? 'Selesai' : 'Diproses' }}</dd>
                </div>
                <div class="flex items-start gap-3">
                    <dt class="w-32 shrink-0 text-slate-500 font-medium">Pelapor</dt>
                    <dd class="text-slate-800 font-semibold">{{ $report->user->name ?? 'Anonim' }}</dd>
                </div>
            </dl>
        </div>

        <!-- Open in app CTA -->
        <div class="bg-white rounded-2xl p-5 shadow-sm mb-4 text-center">
            <p class="text-sm text-slate-600 mb-3">Lihat laporan ini langsung di aplikasi Tolak Bencana</p>
            <a href="tolakbencana://report/{{ $report->id }}"
               class="inline-flex w-full items-center justify-center gap-2 bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm px-5 py-3 rounded-xl transition">
                <i class="fas fa-mobile-screen-button"></i> Buka di Aplikasi
            </a>
            <p class="text-[11px] text-slate-400 mt-3">Belum punya aplikasi? <a href="{{ route('download.apk') }}" class="text-teal-600 font-semibold hover:underline">Download APK</a></p>
        </div>
    </main>

    <footer class="bg-slate-800 text-slate-300">
        <div class="max-w-3xl mx-auto px-4 py-5 text-center text-xs">
            <p class="font-bold text-white">TOLAK BENCANA</p>
            <p class="mt-1">Platform Tanggap Bencana dan Informasi Evakuasi Terpadu</p>
        </div>
    </footer>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>

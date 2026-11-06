<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="TOLAK BENCANA (Platform Tanggap Bencana dan Informasi Evakuasi Terpadu) - Platform crowdsourcing GIS mitigasi dan tanggap bencana di Kabupaten Indramayu.">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <title>TOLAK BENCANA - Platform Tanggap Bencana dan Informasi Evakuasi Terpadu</title>

    <!-- Google Fonts: Poppins (for body) & Fredoka/Outfit (for headers) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Leaflet.js CSS (For public flood map) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Style configuration -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Fredoka', sans-serif;
        }
        
        /* Premium background radial circles */
        .kw-glow-1 {
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background-color: #15803D;
            opacity: 0.06;
            filter: blur(50px);
            z-index: 0;
            pointer-events: none;
        }

        .kw-glow-2 {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background-color: #1E3A8A;
            opacity: 0.05;
            filter: blur(60px);
            z-index: 0;
            pointer-events: none;
        }

        .kw-glow-3 {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background-color: #F97316;
            opacity: 0.04;
            filter: blur(40px);
            z-index: 0;
            pointer-events: none;
        }

        /* Glassmorphic elements */
        .nav-backdrop {
            background: rgba(249, 250, 251, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.7);
        }

        /* Swiper / Hero Banner shadow and layout */
        .hero-banner {
            background: linear-gradient(135deg, rgba(15, 118, 126, 0.48) 0%, rgba(30, 59, 138, 0.45) 100%), url('{{ asset('images/hero.png') }}') no-repeat center center;
            background-size: cover;
            box-shadow: 0 20px 40px -15px rgba(15, 118, 126, 0.3);
        }

        /* Program / Feature Cards Style */
        .kw-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(229, 231, 235, 0.6);
            will-change: transform;
        }

        .kw-card:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: 0 20px 30px -10px rgba(15, 118, 126, 0.15);
            border-color: rgba(15, 118, 126, 0.3);
        }

        /* Leaflet custom map containers */
        .leaflet-container {
            background-color: #f3f4f6 !important;
        }
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            display: inline-block;
            animation: marquee 25s linear infinite;
        }
    </style>

    <!-- Vite Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen text-slate-800 antialiased flex flex-col relative overflow-x-hidden">

    <!-- Running Text Warning Ticker -->
    @if(isset($announcements) && $announcements->isNotEmpty())
    <div class="bg-gradient-to-r from-red-600 via-orange-500 to-red-600 text-white text-[11px] font-bold py-2.5 px-4 shadow-sm z-50 relative overflow-hidden flex items-center gap-3">
        <span class="bg-white text-red-600 px-2 py-0.5 rounded-md font-extrabold uppercase shrink-0 tracking-wider animate-pulse flex items-center gap-1">
            <i class="fa-solid fa-circle-exclamation"></i> {{ $announcements->first()->key }}
        </span>
        <div class="relative w-full overflow-hidden whitespace-nowrap">
            <div class="inline-block animate-marquee">
                @foreach($announcements as $index => $announcement)
                    {{ $announcement->message }}@if(!$loop->last) &nbsp;&nbsp;&nbsp;••&nbsp;&nbsp;&nbsp; @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Background Decoration Bubbles (bounded to prevent horizontal/vertical overflow) -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="kw-glow-1 top-20 -left-20"></div>
        <div class="kw-glow-2 top-[120vh] -right-20"></div>
        <div class="kw-glow-3 top-[220vh] left-[20vw]"></div>
    </div>

    <!-- Navigation Bar -->
    <header class="sticky top-0 z-40 nav-backdrop">
        <nav role="navigation" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="TOLAK BENCANA Logo" class="h-10 w-auto rounded-xl bg-white p-0.5 shadow-sm object-contain" loading="lazy">
                <div>
                    <span class="font-extrabold text-xl tracking-wider text-[#15803D] block">TOLAK BENCANA</span>
                    <span class="text-[9px] text-[#1E3A8A] uppercase tracking-widest font-extrabold block -mt-1">Mitigasi & Tanggap Bencana</span>
                </div>
            </a>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-6">
                <ul class="flex items-center gap-6">
                    <li>
                        <a href="{{ route('home') }}" class="font-semibold text-sm text-[#15803D] transition-colors pb-1">Home</a>
                    </li>
                    <li>
                        <a href="/#fitur" class="font-semibold text-sm text-slate-500 hover:text-[#15803D] transition-colors pb-1">Fitur Utama</a>
                    </li>
                    <li>
                        <a href="/#peta" class="font-semibold text-sm text-slate-500 hover:text-[#15803D] transition-colors pb-1">Peta Sebaran</a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="font-semibold text-sm text-slate-500 hover:text-[#15803D] transition-colors pb-1">Tentang Aplikasi</a>
                    </li>
                    <li>
                        <a href="{{ route('faq') }}" class="font-semibold text-sm text-slate-500 hover:text-[#15803D] transition-colors pb-1">Tanya Jawab (FAQ)</a>
                    </li>
                </ul>
            </div>

            <!-- Mobile Menu Toggle -->
            <button type="button" id="mobile-menu-toggle" aria-label="Buka menu" aria-expanded="false" class="md:hidden flex items-center justify-center w-10 h-10 rounded-xl text-[#15803D] hover:bg-slate-100 transition-colors focus:outline-none">
                <i class="fa-solid fa-bars text-xl" id="mobile-menu-icon"></i>
            </button>
        </nav>

        <!-- Mobile Overlay Menu -->
        <div id="mobile-menu" class="md:hidden hidden fixed inset-x-0 top-16 bottom-0 z-30">
            <div id="mobile-menu-backdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
            <div class="relative bg-white/95 backdrop-blur-sm shadow-2xl border-b border-slate-100">
                <nav class="mx-auto max-w-7xl px-4 sm:px-6 py-4 flex flex-col gap-1">
                    <a href="{{ route('home') }}" class="mobile-nav-link font-semibold text-sm text-[#15803D] px-4 py-3 rounded-xl transition-colors">Home</a>
                    <a href="/#fitur" class="mobile-nav-link font-semibold text-sm text-slate-500 hover:text-[#15803D] px-4 py-3 rounded-xl transition-colors">Fitur Utama</a>
                    <a href="/#peta" class="mobile-nav-link font-semibold text-sm text-slate-500 hover:text-[#15803D] px-4 py-3 rounded-xl transition-colors">Peta Sebaran</a>
                    <a href="{{ route('about') }}" class="mobile-nav-link font-semibold text-sm text-slate-500 hover:text-[#15803D] px-4 py-3 rounded-xl transition-colors">Tentang Aplikasi</a>
                    <a href="{{ route('faq') }}" class="mobile-nav-link font-semibold text-sm text-slate-500 hover:text-[#15803D] px-4 py-3 rounded-xl transition-colors">Tanya Jawab (FAQ)</a>
                </nav>
            </div>
        </div>
    </header>

<!-- Welcome Banner -->
    <section class="relative overflow-hidden py-10" aria-labelledby="hero-title">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <!-- Hero Banner with Rounded-3xl -->
            <div class="relative h-[420px] sm:h-[500px] md:h-[600px] lg:h-[65vh] rounded-3xl overflow-hidden mb-8 hero-banner flex flex-col justify-center items-center text-center p-6 sm:p-12 text-white relative">
                
                <!-- Overlay subtle pattern background -->
                <div class="absolute inset-0 bg-cover bg-center opacity-10 pointer-events-none rounded-3xl" style="background-image: url('https://tile.openstreetmap.org/10/300/300.png');"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent rounded-3xl"></div>
                
                <!-- Text Overlays -->
                <div class="relative z-10 max-w-3xl flex flex-col items-center gap-6">
                    
                    <!-- Badge row -->
                    <div class="flex flex-wrap items-center justify-center gap-4 text-[9px] font-extrabold tracking-widest text-white/80">
                        <span class="bg-white/15 px-2 py-0.5 rounded-md"><i class="fa-solid fa-shield-halved mr-1"></i> Terverifikasi BPBD Indramayu</span>
                        <span class="hidden sm:inline text-white/30">|</span>
                        <span class="bg-white/15 px-2 py-0.5 rounded-md"><i class="fa-solid fa-code mr-1"></i> Didukung Mobile GIS</span>
                        <span class="hidden sm:inline text-white/30">|</span>
                        <span class="bg-white/15 px-2 py-0.5 rounded-md"><i class="fa-solid fa-users mr-1"></i> Partisipasi Publik</span>
                    </div>

                    <!-- Title -->
                    <h1 id="hero-title" class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-black leading-tight drop-shadow-lg">
                        Build the <span class="text-[#F97316]">Resilience</span>, One Report at a Time!
                    </h1>
                    
                    <!-- Subtitle -->
                    <p class="text-sm sm:text-base font-light text-white/80 max-w-2xl leading-relaxed">
                        Mitigasi banjir rob, luapan sungai, dan genangan kota di Kabupaten Indramayu kini lebih andal dan terpadu. Hubungkan laporan warga, dapatkan rekomendasi evakuasi aman berbasis Mobile GIS.
                    </p>

                    <!-- CTA Button -->
                    <a href="{{ $appInfo && $appInfo->apk_path ? route('download.apk') : '#unduh' }}" class="inline-flex items-center gap-2 rounded-xl px-6 sm:px-8 py-3.5 bg-[#F97316] text-white font-extrabold text-sm shadow-xl hover:scale-105 hover:bg-[#EA580C] transition-all duration-200">
                        <i class="fa-brands fa-android text-base"></i>
                        <span>Unduh Aplikasi Sekarang</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Real-time Stats Section -->
    <section class="relative z-10 -mt-10 mx-auto max-w-6xl px-4">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 sm:p-8 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="flex flex-col items-center">
                <span class="text-3xl sm:text-4xl font-black text-[#15803D] tracking-tight">{{ $stats['total_reports'] ?? 0 }}</span>
                <span class="text-[10px] uppercase font-extrabold text-slate-400 mt-1">Laporan Warga</span>
            </div>
            <div class="flex flex-col items-center border-l border-slate-100">
                <span class="text-3xl sm:text-4xl font-black text-[#1E3A8A] tracking-tight">{{ $stats['active_shelters'] ?? 0 }}</span>
                <span class="text-[10px] uppercase font-extrabold text-slate-400 mt-1">Shelter Siaga</span>
            </div>
            <div class="flex flex-col items-center border-l border-slate-100">
                <span class="text-3xl sm:text-4xl font-black text-emerald-600 tracking-tight">{{ $stats['resolved_cases'] ?? 0 }}</span>
                <span class="text-[10px] uppercase font-extrabold text-slate-400 mt-1">Selesai / Surut</span>
            </div>
            <div class="flex flex-col items-center border-l border-slate-100">
                <span class="text-3xl sm:text-4xl font-black text-[#F97316] tracking-tight">{{ $stats['monitored_points'] ?? 0 }}</span>
                <span class="text-[10px] uppercase font-extrabold text-slate-400 mt-1">Titik Dipantau</span>
            </div>
        </div>
    </section>

    <!-- Interactive Mockup & About (Premium content columns) -->
    <section class="py-16 bg-white border-y border-slate-100 relative z-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left: Smartphone Mockup Image -->
                <div class="relative flex justify-center items-center">
                    <div class="absolute w-72 h-72 sm:w-80 sm:h-80 bg-[#15803D] rounded-full opacity-10 blur-2xl z-0"></div>
                    <img src="{{ asset('images/phone.png') }}" alt="TOLAK BENCANA Smartphone Mockup" class="relative z-10 w-72 sm:w-80 h-auto drop-shadow-2xl hover:scale-105 transition-transform duration-300" loading="lazy">
                </div>

                <!-- Right: Text Content -->
                <div class="space-y-6 px-4 text-center md:text-left">
                    <div class="w-12 h-12 bg-[#DCFCE7] rounded-2xl flex items-center justify-center text-[#15803D] mx-auto md:mx-0 shadow-sm">
                        <i class="fa-solid fa-circle-question text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-extrabold text-[#15803D] leading-tight">
                        Tentang Sistem TOLAK BENCANA
                    </h2>
                    <p class="text-sm text-slate-500 leading-relaxed text-justify">
                        Kabupaten Indramayu memiliki karakteristik geografis unik sebagai wilayah hilir dan pesisir. Hal ini membuat Indramayu tidak hanya rentan terhadap banjir rob (pasang laut) di sepanjang pantai, tetapi juga rawan terhadap banjir luapan sungai (kiriman) akibat jebolnya tanggul, serta banjir genangan kota akibat buruknya sistem drainase dan solokan saat curah hujan tinggi.
                    </p>
                    <p class="text-sm text-slate-500 leading-relaxed text-justify">
                        Sistem TOLAK BENCANA hadir guna merespons kompleksitas kerentanan tersebut. Melalui sinergi warga sebagai pelapor dan Badan Penanggulangan Bencana Daerah (BPBD) sebagai verifikator, informasi siaga dan tanggap bencana banjir dapat disalurkan secara cepat, tepat, dan transparan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Us Section (Why Us features) -->
    <section id="fitur" class="py-20 bg-slate-50 border-b border-slate-100 relative z-10"> 
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-[#1E3A8A]">Fitur Unggulan</span>
                <h2 class="text-3xl font-extrabold text-[#15803D] leading-tight mt-2">
                    Kenapa Menggunakan TOLAK BENCANA?
                </h2>
            </div>
            
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Box 1 -->
                <div class="kw-card bg-white p-7 rounded-2xl flex flex-col gap-4">
                    <img src="https://alhazen.academy/assets/kids/index-why/icon-why1.png" alt="Laporan Cepat" class="w-12 h-12 object-contain" loading="lazy" decoding="async">
                    <h3 class="text-lg font-bold text-slate-800">Laporan Cepat 3-Ketuk</h3>
                    <p class="text-xs text-slate-500 leading-relaxed text-justify">
                        Warga dapat melaporkan kejadian banjir hanya dalam maksimal tiga ketukan layar. Foto banjir otomatis menyematkan titik koordinat GPS yang akurat.
                    </p>
                </div>

                <!-- Box 2 -->
                <div class="kw-card bg-white p-7 rounded-2xl flex flex-col gap-4">
                    <img src="https://alhazen.academy/assets/kids/index-why/icon-why2.png" alt="Rute Evakuasi" class="w-12 h-12 object-contain" loading="lazy" decoding="async">
                    <h3 class="text-lg font-bold text-slate-800">Rute Evakuasi Dinamis</h3>
                    <p class="text-xs text-slate-500 leading-relaxed text-justify">
                        Peta rute aman bebas genangan (hijau) ke shelter terdekat yang terus terbarui secara otomatis mengikuti tinggi muka air terkini.
                    </p>
                </div>

                <!-- Box 3 -->
                <div class="kw-card bg-white p-7 rounded-2xl flex flex-col gap-4">
                    <img src="https://alhazen.academy/assets/kids/index-why/icon-why3.png" alt="Alarm Suara" class="w-12 h-12 object-contain" loading="lazy" decoding="async">
                    <h3 class="text-lg font-bold text-slate-800">Notifikasi Suara "Banjar ati!"</h3>
                    <p class="text-xs text-slate-500 leading-relaxed text-justify">
                        Peringatan dini khusus menggunakan rekaman suara bilingual dengan aksen Sunda Indramayu yang akrab bagi masyarakat lokal dan lansia pesisir.
                    </p>
                </div>

                <!-- Box 4 -->
                <div class="kw-card bg-white p-7 rounded-2xl flex flex-col gap-4">
                    <img src="https://alhazen.academy/assets/kids/index-why/icon-why4.png" alt="Offline Mode" class="w-12 h-12 object-contain" loading="lazy" decoding="async">
                    <h3 class="text-lg font-bold text-slate-800">Penyimpanan Offline</h3>
                    <p class="text-xs text-slate-500 leading-relaxed text-justify">
                        Data laporan darurat akan disimpan di memori lokal saat koneksi internet terputus, dan otomatis terkirim segera setelah jaringan aktif kembali.
                    </p>
                </div>

                <!-- Box 5 -->
                <div class="kw-card bg-white p-7 rounded-2xl flex flex-col gap-4">
                    <img src="https://alhazen.academy/assets/kids/index-why/icon-why5.png" alt="BPBD Validation" class="w-12 h-12 object-contain" loading="lazy" decoding="async">
                    <h3 class="text-lg font-bold text-slate-800">Dashboard Kontrol BPBD</h3>
                    <p class="text-xs text-slate-500 leading-relaxed text-justify">
                        Admin BPBD memvalidasi laporan warga, memetakan shelter aktif, dan mengaktifkan sirene darurat dari satu dashboard terpusat yang aman.
                    </p>
                </div>

                <!-- Box 6 -->
                <div class="kw-card bg-white p-7 rounded-2xl flex flex-col gap-4">
                    <img src="https://alhazen.academy/assets/kids/index-why/icon-why6.png" alt="STEM/GIS" class="w-12 h-12 object-contain" loading="lazy" decoding="async">
                    <h3 class="text-lg font-bold text-slate-800">Analisis Spasial GIS</h3>
                    <p class="text-xs text-slate-500 leading-relaxed text-justify">
                        Melakukan visualisasi data titik bencana secara geografis (SIG) untuk mempermudah pemerintah dalam menganalisis daerah rawan banjir rob, luapan sungai, maupun genangan kota.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mitigasi Guide Section -->
    <section class="py-20 bg-white border-b border-slate-100 relative z-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-[#F97316]">Panduan Siaga</span>
                <h2 class="text-3xl font-extrabold text-[#15803D] leading-tight mt-2">
                    Langkah Mitigasi Saat Banjir Melanda
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-2xl bg-amber-50 text-[#F97316] flex items-center justify-center text-2xl font-black mb-4 relative shadow-sm border border-amber-100/50">
                        1
                        <span class="absolute -right-4 top-1/2 -translate-y-1/2 hidden md:block text-slate-200"><i class="fa-solid fa-angle-right"></i></span>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Pantau Peta Distribusi</h3>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-xs">
                        Buka website TOLAK BENCANA untuk memantau sebaran tinggi genangan rob terbaru secara real-time.
                    </p>
                </div>
                <!-- Step 2 -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-[#1E3A8A] flex items-center justify-center text-2xl font-black mb-4 relative shadow-sm border border-blue-100/50">
                        2
                        <span class="absolute -right-4 top-1/2 -translate-y-1/2 hidden md:block text-slate-200"><i class="fa-solid fa-angle-right"></i></span>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Siapkan Tas Siaga</h3>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-xs">
                        Kemas berkas penting, obat-obatan, senter, makanan instan, dan air bersih dalam tas antiair.
                    </p>
                </div>
                <!-- Step 3 -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl font-black mb-4 relative shadow-sm border border-emerald-100/50">
                        3
                        <span class="absolute -right-4 top-1/2 -translate-y-1/2 hidden md:block text-slate-200"><i class="fa-solid fa-angle-right"></i></span>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Ikuti Rute Evakuasi</h3>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-xs">
                        Gunakan rute hijau bebas genangan di peta TOLAK BENCANA menuju shelter terdekat yang aman.
                    </p>
                </div>
                <!-- Step 4 -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center text-2xl font-black mb-4 shadow-sm border border-red-100/50">
                        4
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Hubungi Kontak Darurat</h3>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-xs">
                        Segera tekan tombol panggil bantuan darurat jika membutuhkan evakuasi atau pertolongan medis.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Public Live Map Section -->
    <section id="peta" class="py-20 bg-white border-b border-slate-100 relative z-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 flex flex-col gap-3">
                <span class="text-xs font-bold uppercase tracking-widest text-[#F97316]">Peta Distribusi Siaga</span>
                <h2 class="text-3xl font-extrabold text-[#15803D] tracking-tight">Kondisi Sebaran Banjir Indramayu</h2>
                <p class="text-slate-500 max-w-xl mx-auto text-xs leading-relaxed">
                    Peta publik interaktif di bawah ini menunjukkan laporan bencana dari warga pesisir yang terverifikasi secara real-time.
                </p>
            </div>

            <!-- Map and Shelter Grid layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                <!-- Map (8 Columns) -->
                <div class="lg:col-span-8 h-[350px] sm:h-[450px] md:h-[500px] rounded-3xl shadow-xl border border-slate-200/60 overflow-hidden relative bg-slate-100" id="public-map">
                    <!-- Map loading overlay -->
                    <div class="absolute inset-0 bg-slate-50 z-40 flex items-center justify-center font-semibold text-[#15803D]" id="map-loader">
                        <i class="fa-solid fa-spinner animate-spin text-2xl mr-3"></i> Memuat Peta Distribusi...
                    </div>
                </div>
                
                <!-- Shelter List (4 Columns) -->
                <div class="lg:col-span-4 flex flex-col gap-4 max-h-[500px] overflow-y-auto bg-slate-50 rounded-3xl border border-slate-200/60 p-4 sm:p-5">                    <h3 class="font-bold text-sm text-[#15803D] flex items-center gap-2 mb-1"><i class="fa-solid fa-house-chimney text-[#1E3A8A]"></i> Shelter Evakuasi Siaga</h3>
                    
                    @forelse($shelters as $shelter)
                        <div class="bg-white rounded-2xl p-4 border border-slate-200/40 shadow-sm flex flex-col gap-2 hover:scale-[1.01] transition-transform">
                            <div class="flex justify-between items-start gap-2">
                                <span class="font-bold text-xs text-slate-800 line-clamp-1">{{ $shelter->name }}</span>
                                <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded {{ $shelter->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $shelter->status === 'active' ? 'AKTIF' : 'PENUH' }}
                                </span>
                            </div>
                            <p class="text-[10px] text-slate-400 line-clamp-2"><i class="fa-solid fa-location-dot"></i> {{ $shelter->address }}</p>
                            
                            <div class="grid grid-cols-2 gap-2 mt-1 text-[10px]">
                                <div class="bg-slate-50 rounded-lg p-1.5 flex flex-col">
                                    <span class="text-slate-400 font-medium">Kapasitas</span>
                                    <span class="font-bold text-slate-700">{{ $shelter->capacity }} Jiwa</span>
                                </div>
                                <div class="bg-slate-50 rounded-lg p-1.5 flex flex-col">
                                    <span class="text-slate-400 font-medium">Keterisian</span>
                                    <span class="font-bold {{ $shelter->current_occupancy >= $shelter->capacity ? 'text-red-500' : 'text-[#1E3A8A]' }}">
                                        {{ $shelter->current_occupancy }} Jiwa
                                    </span>
                                </div>
                            </div>
                            
                            @if($shelter->contact)
                                <a href="tel:{{ $shelter->contact }}" class="text-center font-bold text-[9px] text-[#15803D] hover:text-[#0a5258] mt-1 border-t border-slate-100 pt-2 flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-phone"></i> Hubungi Penanggung Jawab
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-xs text-slate-400 py-12">
                            <i class="fa-solid fa-house-circle-xmark text-2xl block mb-2 text-slate-300"></i> Belum ada shelter terdaftar.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- Dynamic Help Contacts Section -->
    <section id="kontak-siaga" class="py-20 bg-slate-50 border-b border-slate-100 relative z-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-[#1E3A8A]">Bantuan Segera</span>
                <h2 class="text-3xl font-extrabold text-[#15803D] leading-tight mt-2">
                    Kontak Siaga & Darurat Bencana
                </h2>
                <p class="text-slate-500 max-w-xl mx-auto text-xs leading-relaxed mt-2">
                    Gunakan daftar kontak darurat di bawah ini untuk melaporkan kondisi genting atau mendapatkan pertolongan evakuasi langsung.
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($helpContacts as $contact)
                    @php
                        $btnPrefix = ($contact->icon === 'whatsapp') ? 'fa-brands' : 'fa-solid';
                        $actionUrl = '#';
                        if ($contact->type === 'phone') $actionUrl = 'tel:' . $contact->value;
                        elseif ($contact->type === 'email') $actionUrl = 'mailto:' . $contact->value;
                        elseif ($contact->type === 'website') $actionUrl = $contact->value;
                        elseif ($contact->type === 'whatsapp') $actionUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $contact->value);
                    @endphp
                    <div class="bg-white rounded-2xl p-6 border border-slate-200/40 shadow-sm flex flex-col gap-4 hover:scale-[1.02] transition-transform">
                        <div class="w-12 h-12 rounded-xl bg-[#DCFCE7] text-[#15803D] flex items-center justify-center text-xl shrink-0">
                            <i class="{{ $btnPrefix }} fa-{{ $contact->icon }}"></i>
                        </div>
                        <div>
                            <span class="text-[9px] text-[#1E3A8A] uppercase font-bold tracking-wider">{{ \App\Support\UiHelper::contactType($contact->type) }}</span>
                            <h3 class="font-bold text-sm text-slate-800 line-clamp-1 mt-0.5">{{ $contact->title }}</h3>
                            <p class="text-xs text-slate-500 font-semibold truncate mt-1">{{ $contact->value }}</p>
                        </div>
                        <a href="{{ $actionUrl }}" target="_blank" class="w-full text-center font-bold text-xs bg-[#15803D] hover:bg-[#0a5258] text-white py-2 rounded-xl mt-auto transition-colors flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-paper-plane"></i> Hubungi Sekarang
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center text-slate-400 text-xs py-8">
                        <i class="fa-solid fa-phone-slash text-2xl block mb-2 text-slate-300"></i> Belum ada kontak siaga terdaftar.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Download CTA Section -->
    <section id="unduh" class="py-16 px-6 sm:px-8 bg-[#15803D] text-white text-center relative overflow-hidden">
        <div class="max-w-4xl mx-auto relative z-10 flex flex-col gap-6 items-center">
            <h2 class="text-3xl font-black tracking-tight leading-tight">Bersama Tangguh Hadapi Bencana Banjir</h2>
            <p class="text-white/80 text-sm max-w-lg leading-relaxed">
                Unduh Tolak Bencana Apps secara gratis. Laporkan genangan air di sekitar Anda dan dapatkan panduan evakuasi tercepat ke shelter terdekat.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center mt-2 items-center">
                @if($appInfo && $appInfo->apk_path)
                    <a href="{{ route('download.apk') }}" class="inline-flex items-center gap-3 bg-white text-slate-900 hover:bg-gray-100 font-extrabold px-6 py-3 rounded-xl shadow-md transition-all hover:scale-105 w-full sm:w-auto justify-center">
                        <i class="fa-brands fa-android text-base"></i>
                        <span>Download APK Android</span>
                    </a>
                    @if($appInfo->apk_version)
                        <span class="text-white/70 text-xs">Versi {{ $appInfo->apk_version }} · {{ $appInfo->apk_uploaded_at?->format('d M Y') }}</span>
                    @endif
                @else
                    <span class="text-white/60 text-sm">APK akan segera tersedia.</span>
                @endif
            </div>
        </div>
    </section>

    <!-- Footer (Premium Dark Slate Style) -->
    <footer class="mt-auto bg-[#0F172A] text-white py-16 px-6 sm:px-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-10">
            <!-- Left Info -->
            <div class="md:col-span-5 flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo TOLAK BENCANA" class="w-8 h-8 rounded-lg bg-white p-0.5 object-contain">
                    <span class="font-extrabold text-lg tracking-wider">TOLAK BENCANA</span>
                </div>
                <p class="text-slate-400 text-xs leading-relaxed max-w-sm">
                    Platform Tanggap Bencana dan Informasi Evakuasi Terpadu berbasis Mobile GIS untuk mitigasi bencana, pemetaan posko evakuasi, dan pelaporan darurat secara real-time.
                </p>
                <span class="text-[10px] text-slate-500 block mt-2">&copy; 2026 TOLAK BENCANA. Hak Cipta Dilindungi.</span>
            </div>

            <!-- Middle links -->
            <div class="md:col-span-3 flex flex-col gap-3.5">
                <h4 class="font-bold text-sm tracking-wider uppercase text-[#1E3A8A]">Navigasi Web</h4>
                <div class="flex flex-col gap-2 text-xs text-slate-400">
                    <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                    <a href="/#fitur" class="hover:text-white transition-colors">Fitur Utama</a>
                    <a href="/#peta" class="hover:text-white transition-colors">Peta Sebaran</a>
                    <a href="{{ route('about') }}" class="hover:text-white transition-colors">Tentang Aplikasi</a>
                    <a href="{{ route('faq') }}" class="hover:text-white transition-colors">Tanya Jawab (FAQ)</a>
                </div>
            </div>

            <!-- Right Contacts -->
            <div class="md:col-span-4 flex flex-col gap-3.5">
                <h4 class="font-bold text-sm tracking-wider uppercase text-[#F97316]">Kontak Siaga Darurat</h4>
                <div class="flex flex-col gap-2 text-xs text-slate-400 mb-4">
                    <span class="flex items-center gap-2"><i class="fa-solid fa-phone w-4 text-center"></i> BPBD Indramayu: (0234) 276996</span>
                    <span class="flex items-center gap-2"><i class="fa-solid fa-map-location-dot w-4 text-center"></i> Jl. Gatot Subroto No.10, Indramayu</span>
                    <span class="flex items-center gap-2"><i class="fa-solid fa-envelope w-4 text-center"></i> support@tolakbencana.id</span>
                </div>
                
                <!-- Discreet Admin Login portal link in footer -->
                <div class="border-t border-slate-800 pt-3">
                    <a href="{{ route('admin.login') }}" class="text-[10px] text-slate-500 hover:text-white transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-lock"></i> Portal Admin BPBD
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Leaflet.js Library (For maps) -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mobile menu toggle
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuIcon = document.getElementById('mobile-menu-icon');
            if (mobileMenuToggle && mobileMenu) {
                mobileMenuToggle.addEventListener('click', function () {
                    const isOpen = mobileMenu.classList.toggle('hidden') === false;
                    mobileMenuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    if (mobileMenuIcon) {
                        mobileMenuIcon.className = isOpen ? 'fa-solid fa-xmark text-xl' : 'fa-solid fa-bars text-xl';
                    }
                });
                mobileMenu.querySelectorAll('.mobile-nav-link').forEach(function (link) {
                    link.addEventListener('click', function () {
                        mobileMenu.classList.add('hidden');
                        mobileMenuToggle.setAttribute('aria-expanded', 'false');
                        if (mobileMenuIcon) {
                            mobileMenuIcon.className = 'fa-solid fa-bars text-xl';
                        }
                    });
                });

                // Close when tapping the backdrop
                const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
                if (mobileMenuBackdrop) {
                    mobileMenuBackdrop.addEventListener('click', function () {
                        mobileMenu.classList.add('hidden');
                        mobileMenuToggle.setAttribute('aria-expanded', 'false');
                        if (mobileMenuIcon) {
                            mobileMenuIcon.className = 'fa-solid fa-bars text-xl';
                        }
                    });
                }
            }

            // Remove Map Loader
            const mapLoader = document.getElementById('map-loader');
            if (mapLoader) mapLoader.remove();

            // Set Bounding Box for Indramayu (South-West to North-East)
            const indramayuBounds = L.latLngBounds(
                L.latLng(-6.55, 107.85), // South-West point
                L.latLng(-6.05, 108.65)  // North-East point
            );

            // Initialize Map Centered at Indramayu with boundaries
            const map = L.map('public-map', {
                center: [-6.27, 108.20],
                zoom: 11,
                minZoom: 10,
                maxZoom: 18,
                maxBounds: indramayuBounds,
                maxBoundsViscosity: 1.0
            });

            // Set Minimal Light Map Tiles (CartoDB Positron)
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                maxZoom: 18,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
            }).addTo(map);

            // Add Custom Marker Icons based on Severity/Status
            const redIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div class='w-6 h-6 rounded-full bg-red-650 border border-white shadow-lg flex items-center justify-center text-white'><i class='fa-solid fa-triangle-exclamation text-[9px] animate-pulse'></i></div>",
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            const orangeIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div class='w-6 h-6 rounded-full bg-orange-500 border border-white shadow-lg flex items-center justify-center text-white'><i class='fa-solid fa-triangle-exclamation text-[9px]'></i></div>",
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            const greenIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div class='w-6 h-6 rounded-full bg-emerald-500 border border-white shadow-lg flex items-center justify-center text-white'><i class='fa-solid fa-check text-[9px]'></i></div>",
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            // Load real data from database via HomeController
            const reports = @json($reports);

            reports.forEach(function (report) {
                if (!report.latitude || !report.longitude) return;

                // Determine icon based on status or severity
                let icon = orangeIcon; // Default warning
                let severityLabel = 'SIAGA PERINGATAN';
                let colorClass = 'text-orange-500';

                if (report.status === 'resolved') {
                    icon = greenIcon;
                    severityLabel = 'KONDISI AMAN (SURUT)';
                    colorClass = 'text-emerald-650';
                } else if (report.severity === 'high' || report.severity === 'critical') {
                    icon = redIcon;
                    severityLabel = 'SIAGA DANGER / TINGGI';
                    colorClass = 'text-red-600';
                }

                // Add marker
                L.marker([parseFloat(report.latitude), parseFloat(report.longitude)], {icon: icon})
                    .addTo(map)
                    .bindPopup(
                        "<div class='p-1.5 w-44 text-[#1F2937] bg-white rounded-lg'>" +
                        "<span class='font-bold text-xs block " + colorClass + "'>" + severityLabel + "</span>" +
                        "<span class='font-bold text-sm block mt-0.5 text-gray-900'>" + (report.address || 'Pesisir Indramayu') + "</span>" +
                        "<span class='text-[10px] text-gray-500 block mt-1 leading-relaxed'>" + (report.description || 'Laporan banjir rob') + "</span>" +
                        "</div>"
                    );
            });

            // Add Custom Shelter Icon
            const shelterIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div class='w-7 h-7 rounded-full bg-[#15803D] border-2 border-white shadow-lg flex items-center justify-center text-white'><i class='fa-solid fa-house-chimney-medical text-[11px]'></i></div>",
                iconSize: [28, 28],
                iconAnchor: [14, 14]
            });

            // Load real shelters from database via HomeController
            const shelters = @json($shelters);
            shelters.forEach(function (s) {
                if (!s.latitude || !s.longitude) return;

                L.marker([parseFloat(s.latitude), parseFloat(s.longitude)], {icon: shelterIcon})
                    .addTo(map)
                    .bindPopup(
                        "<div class='p-1.5 w-44 text-[#1F2937] bg-white rounded-lg'>" +
                        "<span class='font-bold text-xs block text-[#15803D]'><i class='fa-solid fa-house-shield'></i> SHELTER SIAGA</span>" +
                        "<span class='font-bold text-sm block mt-0.5 text-gray-900'>" + s.name + "</span>" +
                        "<span class='text-[10px] text-gray-500 block mt-1 leading-relaxed'>" + s.address + "</span>" +
                        "<span class='text-[9px] font-bold text-slate-650 block mt-1'>Kapasitas: " + s.capacity + " Jiwa</span>" +
                        "</div>"
                    );
            });
        });
    </script>
</body>
</html>

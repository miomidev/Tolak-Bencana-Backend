<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="KALIWEDI (Kanal Laporan Informasi Warga dan Evakuasi Dinamis Indramayu) - Platform crowdsourcing GIS mitigasi bencana banjir PKM-KC 2026.">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <title>KALIWEDI - Kanal Laporan Informasi Warga dan Evakuasi Dinamis Indramayu</title>

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
        
        /* Alhazen-style background radial circles */
        .alhazen-glow-1 {
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background-color: #1e3a8a;
            opacity: 0.06;
            filter: blur(50px);
            z-index: 0;
            pointer-events: none;
        }

        .alhazen-glow-2 {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background-color: #0f767e;
            opacity: 0.05;
            filter: blur(60px);
            z-index: 0;
            pointer-events: none;
        }

        .alhazen-glow-3 {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background-color: #f97316;
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
            background: linear-gradient(135deg, #1e3a8a 0%, #0f767e 100%);
            box-shadow: 0 20px 40px -15px rgba(30, 58, 138, 0.3);
        }

        /* Program / Feature Cards - Alhazen Card Style */
        .alhazen-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(229, 231, 235, 0.6);
            will-change: transform;
        }

        .alhazen-card:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: 0 20px 30px -10px rgba(15, 118, 126, 0.15);
            border-color: rgba(15, 118, 126, 0.3);
        }

        /* Leaflet custom map containers */
        .leaflet-container {
            background-color: #f3f4f6 !important;
        }
    </style>

    <!-- Vite Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen text-slate-800 antialiased flex flex-col relative overflow-x-hidden">

    <!-- Background Decoration Bubbles (Alhazen Style) -->
    <div class="alhazen-glow-1 top-20 -left-20"></div>
    <div class="alhazen-glow-2 top-[120vh] -right-20"></div>
    <div class="alhazen-glow-3 top-[220vh] left-[20vw]"></div>

    <!-- Navigation Bar -->
    <header class="sticky top-0 z-40 nav-backdrop">
        <nav role="navigation" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="KALIWEDI Logo" class="h-10 w-auto rounded-xl bg-white p-0.5 shadow-sm object-contain" loading="lazy">
                <div>
                    <span class="font-extrabold text-xl tracking-wider text-[#1E3A8A] block">KALIWEDI</span>
                    <span class="text-[9px] text-[#0F767E] uppercase tracking-widest font-extrabold block -mt-1">PKM-KC Mitigasi Banjir</span>
                </div>
            </a>
            
            <!-- Desktop Menu -->
            <div class="flex items-center gap-6">
                <ul class="flex items-center gap-6">
                    <li>
                        <a href="{{ route('home') }}" class="font-semibold text-sm text-[#1E3A8A] transition-colors pb-1">Home</a>
                    </li>
                    <li>
                        <a href="/#fitur" class="font-semibold text-sm text-slate-500 hover:text-[#1E3A8A] transition-colors pb-1">Fitur Utama</a>
                    </li>
                    <li>
                        <a href="/#peta" class="font-semibold text-sm text-slate-500 hover:text-[#1E3A8A] transition-colors pb-1">Peta Sebaran</a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="font-semibold text-sm text-slate-500 hover:text-[#1E3A8A] transition-colors pb-1">Tentang Aplikasi</a>
                    </li>
                    <li>
                        <a href="{{ route('faq') }}" class="font-semibold text-sm text-slate-500 hover:text-[#1E3A8A] transition-colors pb-1">Tanya Jawab (FAQ)</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="beranda" class="relative overflow-hidden py-6" aria-labelledby="hero-title">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <!-- Main Hero Banner with Rounded-3xl (Mimicking Alhazen Swiper structure) -->
            <div class="relative h-[550px] md:h-[600px] lg:h-[65vh] rounded-3xl overflow-hidden mb-8 hero-banner flex flex-col justify-center items-center text-center p-8 sm:p-12 text-white relative">
                
                <!-- Overlay subtle pattern background -->
                <div class="absolute inset-0 bg-cover bg-center opacity-10 pointer-events-none rounded-3xl" style="background-image: url('https://tile.openstreetmap.org/10/300/300.png');"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-transparent to-transparent rounded-3xl"></div>
                
                <!-- Text Overlays -->
                <div class="relative z-10 max-w-3xl flex flex-col items-center gap-6">


                    <h1 id="hero-title" class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black leading-tight drop-shadow-lg">
                        Build the <span class="text-[#F97316]">Resilience</span>, One Report at a Time!
                    </h1>
                    
                    <p class="text-sm sm:text-base font-light text-white/80 max-w-2xl leading-relaxed">
                        Mitigasi genangan dan banjir rob di pesisir Indramayu kini lebih andal dan terpadu. Hubungkan laporan warga, dapatkan rekomendasi evakuasi aman berbasis Mobile GIS.
                    </p>

                    <!-- CTA Button -->
                    <a href="#unduh" class="inline-flex items-center gap-2 rounded-xl px-8 py-3.5 bg-[#F97316] text-white font-extrabold text-sm shadow-xl hover:scale-105 hover:bg-[#EA580C] transition-all duration-200">
                        <i class="fa-brands fa-android text-base"></i>
                        <span>Unduh Aplikasi Sekarang</span>
                    </a>
                </div>
            </div>

            <!-- Certified Badges Centered -->
            <div class="px-8 pb-4 text-center z-10 relative"> 
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 text-xs font-semibold text-slate-500"> 
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-shield-halved text-[#0F767E]"></i> Terverifikasi BPBD Indramayu</span>
                    <span class="hidden sm:inline text-slate-300">|</span>
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-code text-[#1E3A8A]"></i> Didukung Teknologi Mobile GIS</span>
                    <span class="hidden sm:inline text-slate-300">|</span>
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-users text-[#F97316]"></i> Berbasis Partisipasi Publik</span>
                </div>
            </div>

        </div>
    </section>

    <!-- Interactive Mockup & About (Alhazen-style content columns) -->
    <section class="py-16 bg-white border-y border-slate-100 relative z-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left: Mascot/Laptop Mockup representation -->
                <div class="relative flex justify-center items-center">
                    <div class="absolute w-72 h-72 sm:w-80 sm:h-80 bg-[#0F767E] rounded-full opacity-10 blur-2xl z-0"></div>
                    <!-- Rounded floating app preview card -->
                    <div class="relative z-10 w-72 h-[480px] bg-slate-900 rounded-[36px] p-3 shadow-2xl border border-slate-800 flex flex-col overflow-hidden">
                        <div class="w-full h-full bg-[#f8fafc] rounded-[26px] overflow-hidden flex flex-col relative border border-white/5">
                            <!-- App Header -->
                            <div class="bg-[#1E3A8A] text-white p-3.5 flex items-center justify-between shadow-sm">
                                <span class="font-extrabold text-xs tracking-wider flex items-center gap-1">
                                    <img src="{{ asset('images/logo.png') }}" class="w-4.5 h-4.5 rounded bg-white p-0.5" alt=""> KALIWEDI
                                </span>
                                <i class="fa-regular fa-bell text-xs text-white/80"></i>
                            </div>
                            <!-- Mock Content -->
                            <div class="p-3 flex flex-col gap-2 flex-grow bg-slate-55 relative">
                                <div class="bg-red-50 border border-red-200 text-red-700 p-2.5 rounded-xl text-[9px] font-semibold">
                                    <span class="font-bold">SIAGA MERAH</span> - Air rob laut naik 80cm di Sukra.
                                </div>
                                <div class="flex-grow bg-slate-100 rounded-2xl relative overflow-hidden border border-slate-200/60">
                                    <!-- Mini map screen -->
                                    <div class="absolute inset-0 bg-cover bg-center opacity-50" style="background-image: url('https://tile.openstreetmap.org/10/300/300.png');"></div>
                                    <!-- Pins -->
                                    <span class="absolute top-1/3 left-1/3 w-3 h-3 bg-red-650 rounded-full border border-white shadow animate-ping"></span>
                                    <span class="absolute top-1/3 left-1/3 w-2 h-2 bg-red-605 rounded-full border border-white shadow"></span>
                                    <span class="absolute top-1/2 right-1/4 w-2 h-2 bg-[#F97316] rounded-full border border-white shadow"></span>
                                    <!-- Evacuation route drawing representation -->
                                    <svg class="absolute inset-0 w-full h-full" viewBox="0 0 100 100">
                                        <line x1="33" y1="33" x2="60" y2="40" stroke="#10B981" stroke-width="2.5" stroke-dasharray="3 3"></line>
                                        <line x1="60" y1="40" x2="80" y2="70" stroke="#10B981" stroke-width="2.5"></line>
                                    </svg>
                                    <!-- Floating shelter card -->
                                    <div class="absolute bottom-2 left-2 right-2 bg-white/95 border border-slate-200/50 p-2 rounded-xl shadow-md text-[8px] flex flex-col gap-0.5">
                                        <span class="font-bold text-[#1E3A8A]"><i class="fa-solid fa-house-chimney"></i> Shelter Balai Desa Sukra</span>
                                        <span class="text-emerald-600 font-bold">RUTE AMAN DISARANKAN</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Text Content -->
                <div class="space-y-6 px-4 text-center md:text-left">
                    <div class="w-12 h-12 bg-[#E0F2F1] rounded-2xl flex items-center justify-center text-[#0F767E] mx-auto md:mx-0 shadow-sm">
                        <i class="fa-solid fa-circle-question text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-extrabold text-[#1E3A8A] leading-tight">
                        Tentang Sistem KALIWEDI
                    </h2>
                    <p class="text-sm text-slate-500 leading-relaxed text-justify">
                        <strong>KALIWEDI</strong> merupakan solusi inovatif kebencanaan yang mengawinkan partisipasi warga (*crowdsourcing*) dan pemetaan *Mobile Geographic Information System (Mobile GIS)*. 
                    </p>
                    <p class="text-sm text-slate-500 leading-relaxed text-justify">
                        Sistem ini dikembangkan khusus guna merespons kerentanan genangan banjir rob di pesisir utara Kabupaten Indramayu. Melalui sinergi warga sebagai pelapor dan Badan Penanggulangan Bencana Daerah (BPBD) sebagai verifikator, informasi siaga bencana dapat disalurkan secara cepat, tepat, dan transparan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Us Section (Mimicking Alhazen's Why Us) -->
    <section id="fitur" class="py-20 bg-slate-50 border-b border-slate-100 relative z-10"> 
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-[#0F767E]">Fitur Unggulan</span>
                <h2 class="text-3xl font-extrabold text-[#1E3A8A] leading-tight mt-2">
                    Kenapa Menggunakan KALIWEDI?
                </h2>
            </div>
            
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Box 1 -->
                <div class="alhazen-card bg-white p-7 rounded-2xl flex flex-col gap-4">
                    <img src="https://alhazen.academy/assets/kids/index-why/icon-why1.png" alt="Laporan Cepat" class="w-12 h-12 object-contain" loading="lazy" decoding="async">
                    <h3 class="text-lg font-bold text-slate-800">Laporan Cepat 3-Ketuk</h3>
                    <p class="text-xs text-slate-500 leading-relaxed text-justify">
                        Warga dapat melaporkan kejadian banjir hanya dalam maksimal tiga ketukan layar. Foto banjir otomatis menyematkan titik koordinat GPS yang akurat.
                    </p>
                </div>

                <!-- Box 2 -->
                <div class="alhazen-card bg-white p-7 rounded-2xl flex flex-col gap-4">
                    <img src="https://alhazen.academy/assets/kids/index-why/icon-why2.png" alt="Rute Evakuasi" class="w-12 h-12 object-contain" loading="lazy" decoding="async">
                    <h3 class="text-lg font-bold text-slate-800">Rute Evakuasi Dinamis</h3>
                    <p class="text-xs text-slate-500 leading-relaxed text-justify">
                        Peta rute aman bebas genangan (hijau) ke shelter terdekat yang terus terbarui secara otomatis mengikuti tinggi muka air terkini.
                    </p>
                </div>

                <!-- Box 3 -->
                <div class="alhazen-card bg-white p-7 rounded-2xl flex flex-col gap-4">
                    <img src="https://alhazen.academy/assets/kids/index-why/icon-why3.png" alt="Alarm Suara" class="w-12 h-12 object-contain" loading="lazy" decoding="async">
                    <h3 class="text-lg font-bold text-slate-800">Notifikasi Suara "Banjar ati!"</h3>
                    <p class="text-xs text-slate-500 leading-relaxed text-justify">
                        Peringatan dini khusus menggunakan rekaman suara bilingual dengan aksen Sunda Indramayu yang akrab bagi masyarakat lokal dan lansia pesisir.
                    </p>
                </div>

                <!-- Box 4 -->
                <div class="alhazen-card bg-white p-7 rounded-2xl flex flex-col gap-4">
                    <img src="https://alhazen.academy/assets/kids/index-why/icon-why4.png" alt="Offline Mode" class="w-12 h-12 object-contain" loading="lazy" decoding="async">
                    <h3 class="text-lg font-bold text-slate-800">Penyimpanan Offline</h3>
                    <p class="text-xs text-slate-500 leading-relaxed text-justify">
                        Data laporan darurat akan disimpan di memori lokal saat koneksi internet terputus, dan otomatis terkirim segera setelah jaringan aktif kembali.
                    </p>
                </div>

                <!-- Box 5 -->
                <div class="alhazen-card bg-white p-7 rounded-2xl flex flex-col gap-4">
                    <img src="https://alhazen.academy/assets/kids/index-why/icon-why5.png" alt="BPBD Validation" class="w-12 h-12 object-contain" loading="lazy" decoding="async">
                    <h3 class="text-lg font-bold text-slate-800">Dashboard Kontrol BPBD</h3>
                    <p class="text-xs text-slate-500 leading-relaxed text-justify">
                        Admin BPBD memvalidasi laporan warga, memetakan shelter aktif, dan mengaktifkan sirene darurat dari satu dashboard terpusat yang aman.
                    </p>
                </div>

                <!-- Box 6 -->
                <div class="alhazen-card bg-white p-7 rounded-2xl flex flex-col gap-4">
                    <img src="https://alhazen.academy/assets/kids/index-why/icon-why6.png" alt="STEM/GIS" class="w-12 h-12 object-contain" loading="lazy" decoding="async">
                    <h3 class="text-lg font-bold text-slate-800">Analisis Spasial GIS</h3>
                    <p class="text-xs text-slate-500 leading-relaxed text-justify">
                        Melakukan visualisasi data titik banjir secara geografis (SIG) untuk mempermudah pemerintah dalam menganalisis daerah rawan genangan rob.
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
                <h2 class="text-3xl font-extrabold text-[#1E3A8A] tracking-tight">Kondisi Sebaran Banjir Indramayu</h2>
                <p class="text-slate-500 max-w-xl mx-auto text-xs leading-relaxed">
                    Peta publik interaktif di bawah ini menunjukkan laporan banjir dari warga pesisir yang terverifikasi secara real-time.
                </p>
            </div>

            <!-- Leaflet Map Container with Alhazen card roundings -->
            <div class="w-full h-[500px] rounded-3xl shadow-xl border border-slate-200/60 overflow-hidden relative bg-slate-100" id="public-map">
                <!-- Map loading overlay -->
                <div class="absolute inset-0 bg-slate-50 z-40 flex items-center justify-center font-semibold text-[#1E3A8A]" id="map-loader">
                    <i class="fa-solid fa-spinner animate-spin text-2xl mr-3"></i> Memuat Peta Distribusi...
                </div>
            </div>
        </div>
    </section>

    <!-- Download CTA Section (Alhazen-style banner call) -->
    <section id="unduh" class="py-16 px-6 sm:px-8 bg-gradient-to-r from-[#1E3A8A] to-[#0F767E] text-white text-center relative overflow-hidden">
        <div class="max-w-4xl mx-auto relative z-10 flex flex-col gap-6 items-center">
            <h2 class="text-3xl font-black tracking-tight leading-tight">Bersama Tangguh Hadapi Bencana Rob</h2>
            <p class="text-white/80 text-sm max-w-lg leading-relaxed">
                Unduh KALIWEDI Apps secara gratis. Laporkan genangan air di sekitar Anda dan dapatkan panduan evakuasi tercepat ke shelter terdekat.
            </p>
            <div class="flex gap-4 justify-center mt-2">
                <a href="#" class="inline-flex items-center gap-3 bg-white text-slate-900 hover:bg-gray-100 font-extrabold px-6 py-3 rounded-xl shadow-md transition-all hover:scale-105">
                    <i class="fa-brands fa-android text-base"></i>
                    <span>Download APK Android</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer (Alhazen Dark Slate Style) -->
    <footer class="bg-[#0F172A] text-white py-16 px-6 sm:px-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-10">
            <!-- Left Info -->
            <div class="md:col-span-5 flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo KALIWEDI" class="w-8 h-8 rounded-lg bg-white p-0.5 object-contain">
                    <span class="font-extrabold text-lg tracking-wider">KALIWEDI</span>
                </div>
                <p class="text-slate-400 text-xs leading-relaxed max-w-sm">
                    Kanal Laporan Informasi Warga dan Evakuasi Dinamis Indramayu. Dikembangkan dalam Program Kreativitas Mahasiswa bidang Karsa Cipta (PKM-KC) 2026.
                </p>
                <span class="text-[10px] text-slate-500 block mt-2">&copy; 2026 Tim PKM-KC KALIWEDI. Politeknik Negeri Indramayu.</span>
            </div>

            <!-- Middle links -->
            <div class="md:col-span-3 flex flex-col gap-3.5">
                <h4 class="font-bold text-sm tracking-wider uppercase text-[#0F767E]">Navigasi Web</h4>
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
                    <span class="flex items-center gap-2"><i class="fa-solid fa-envelope w-4 text-center"></i> support@kaliwedi.id</span>
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
        });
    </script>
</body>
</html>

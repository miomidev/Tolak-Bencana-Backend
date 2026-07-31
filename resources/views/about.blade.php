<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Tentang Aplikasi KALIWEDI - Kanal Laporan Informasi Warga dan Evakuasi Dinamis Indramayu.">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <title>Tentang Aplikasi - KALIWEDI</title>

    <!-- Google Fonts: Poppins & Fredoka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;850&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Style configuration -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Fredoka', sans-serif;
        }
        
        .alhazen-glow-1 {
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background-color: #1e3a8a;
            opacity: 0.05;
            filter: blur(50px);
            z-index: 0;
            pointer-events: none;
        }

        .alhazen-glow-2 {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background-color: #0f767e;
            opacity: 0.04;
            filter: blur(50px);
            z-index: 0;
            pointer-events: none;
        }

        .nav-backdrop {
            background: rgba(249, 250, 251, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.7);
        }

        .about-card {
            background: white;
            border: 1px solid rgba(229, 231, 235, 0.8);
            box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.05);
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen text-slate-800 antialiased flex flex-col relative overflow-x-hidden">

    <!-- Glow bubbles -->
    <div class="alhazen-glow-1 top-20 right-0"></div>
    <div class="alhazen-glow-2 top-[50vh] left-0"></div>

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
            
            <div class="flex items-center gap-6">
                <ul class="flex items-center gap-6">
                    <li>
                        <a href="{{ route('home') }}" class="font-semibold text-sm text-slate-500 hover:text-[#1E3A8A] transition-colors pb-1">Home</a>
                    </li>
                    <li>
                        <a href="/#fitur" class="font-semibold text-sm text-slate-500 hover:text-[#1E3A8A] transition-colors pb-1">Fitur Utama</a>
                    </li>
                    <li>
                        <a href="/#peta" class="font-semibold text-sm text-slate-500 hover:text-[#1E3A8A] transition-colors pb-1">Peta Sebaran</a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="font-semibold text-sm text-[#1E3A8A] transition-colors pb-1 border-b-2 border-[#1E3A8A]">Tentang Aplikasi</a>
                    </li>
                    <li>
                        <a href="{{ route('faq') }}" class="font-semibold text-sm text-slate-500 hover:text-[#1E3A8A] transition-colors pb-1">Tanya Jawab (FAQ)</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-4xl mx-auto px-6 sm:px-8 py-16 relative z-10 w-full">
        
        <!-- App Header Section -->
        <div class="text-center flex flex-col items-center gap-4 mb-12">
            <img src="{{ asset('images/logo.png') }}" alt="Logo KALIWEDI" class="w-20 h-20 rounded-3xl bg-white p-2 shadow-lg border border-gray-100 object-contain hover:scale-105 transition-transform">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#1E3A8A] mt-2">
                {{ $appInfo->app_name ?? 'KALIWEDI Apps' }}
            </h1>
            <p class="text-xs font-bold uppercase tracking-wider text-[#0F767E] -mt-2">
                Sistem Informasi Mitigasi Kebencanaan Indramayu
            </p>
        </div>

        <!-- Details Card -->
        <div class="about-card p-8 rounded-3xl mb-8 flex flex-col gap-6">
            <div>
                <h3 class="font-extrabold text-lg text-slate-800 border-b border-gray-100 pb-2 mb-3"><i class="fa-solid fa-circle-info text-[#1E3A8A] mr-2"></i> Deskripsi Aplikasi</h3>
                <p class="text-sm text-slate-500 leading-relaxed text-justify">
                    {{ $appInfo->description ?? 'Deskripsi aplikasi tidak tersedia.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                <div class="bg-slate-50 p-4 rounded-2xl border border-gray-200/60">
                    <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider block">Pengembang</span>
                    <span class="font-bold text-[#1E3A8A] text-xs block mt-1"><i class="fa-solid fa-building-user mr-1.5 text-slate-450"></i> {{ $appInfo->developer ?? 'BPBD Indramayu' }}</span>
                </div>
                <div class="bg-slate-50 p-4 rounded-2xl border border-gray-200/60">
                    <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider block">Dukungan Kontak</span>
                    <span class="font-bold text-[#1E3A8A] text-xs block mt-1"><i class="fa-solid fa-envelope mr-1.5 text-slate-450"></i> {{ $appInfo->support_email ?? 'support@kaliwedi.id' }}</span>
                </div>
            </div>
        </div>

        <!-- Privacy and Terms Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Privacy Card -->
            <div class="about-card p-6 sm:p-8 rounded-3xl flex flex-col gap-4">
                <h3 class="font-extrabold text-base text-slate-800 border-b border-gray-100 pb-2 flex items-center gap-2">
                    <i class="fa-solid fa-user-shield text-[#0F767E]"></i> Kebijakan Privasi
                </h3>
                <div class="text-xs text-slate-500 leading-relaxed text-justify whitespace-pre-line">
                    {{ $appInfo->privacy_policy_content ?? 'Ketentuan kebijakan privasi tidak ditemukan.' }}
                </div>
            </div>

            <!-- Terms Card -->
            <div class="about-card p-6 sm:p-8 rounded-3xl flex flex-col gap-4">
                <h3 class="font-extrabold text-base text-slate-800 border-b border-gray-100 pb-2 flex items-center gap-2">
                    <i class="fa-solid fa-scale-balanced text-[#F97316]"></i> Syarat & Ketentuan
                </h3>
                <div class="text-xs text-slate-500 leading-relaxed text-justify whitespace-pre-line">
                    {{ $appInfo->terms_content ?? 'Syarat dan ketentuan layanan tidak ditemukan.' }}
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-[#0F172A] text-white py-12 px-6 sm:px-8 border-t border-slate-800 mt-auto">
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo KALIWEDI" class="w-8 h-8 rounded-lg bg-white p-0.5 object-contain">
                <span class="font-extrabold text-sm tracking-wider">KALIWEDI PKM-KC</span>
            </div>
            <span class="text-[10px] text-slate-500">&copy; 2026 Tim PKM-KC KALIWEDI. Politeknik Negeri Indramayu.</span>
        </div>
    </footer>

</body>
</html>

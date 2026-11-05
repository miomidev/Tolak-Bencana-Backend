<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Tanya Jawab (FAQ) Aplikasi Tolak Bencana - Platform Tanggap Bencana dan Informasi Evakuasi Terpadu.">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <title>Tanya Jawab (FAQ) - TOLAK BENCANA</title>

    <!-- Google Fonts: Poppins & Fredoka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;805&display=swap" rel="stylesheet">

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
        
        .kw-glow-1 {
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background-color: #15803D;
            opacity: 0.05;
            filter: blur(50px);
            z-index: 0;
            pointer-events: none;
        }

        .kw-glow-2 {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background-color: #1e3a8a;
            opacity: 0.04;
            filter: blur(50px);
            z-index: 0;
            pointer-events: none;
        }

        .kw-glow-3 {
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

        /* Running text marquee animation */
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            display: inline-block;
            animation: marquee 25s linear infinite;
        }

        .nav-backdrop {
            background: rgba(249, 250, 251, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.7);
        }

        /* FAQ Accordion Transitions */
        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease;
        }
        
        .faq-item.active .faq-content {
            max-height: 200px; /* Adjust according to text length, fits average faq answers */
            padding-top: 1rem;
        }

        .faq-item {
            background: white;
            border: 1px solid rgba(229, 231, 235, 0.8);
            box-shadow: 0 4px 15px -5px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .faq-item.active {
            border-color: rgba(15, 118, 126, 0.35);
            box-shadow: 0 10px 25px -10px rgba(15, 118, 126, 0.12);
        }
    </style>

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

    <!-- Background Decoration Bubbles -->
    <div class="kw-glow-1 top-20 -left-20"></div>
    <div class="kw-glow-2 top-[120vh] -right-20"></div>
    <div class="kw-glow-3 top-[220vh] left-[20vw]"></div>

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
            
            <div class="hidden md:flex items-center gap-6">
                <ul class="flex items-center gap-6">
                    <li>
                        <a href="{{ route('home') }}" class="font-semibold text-sm text-slate-500 hover:text-[#15803D] transition-colors pb-1">Home</a>
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
                        <a href="{{ route('faq') }}" class="font-semibold text-sm text-[#15803D] transition-colors pb-1 border-b-2 border-[#15803D]">Tanya Jawab (FAQ)</a>
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
                    <a href="{{ route('home') }}" class="mobile-nav-link font-semibold text-sm text-slate-500 hover:text-[#15803D] px-4 py-3 rounded-xl transition-colors">Home</a>
                    <a href="/#fitur" class="mobile-nav-link font-semibold text-sm text-slate-500 hover:text-[#15803D] px-4 py-3 rounded-xl transition-colors">Fitur Utama</a>
                    <a href="/#peta" class="mobile-nav-link font-semibold text-sm text-slate-500 hover:text-[#15803D] px-4 py-3 rounded-xl transition-colors">Peta Sebaran</a>
                    <a href="{{ route('about') }}" class="mobile-nav-link font-semibold text-sm text-slate-500 hover:text-[#15803D] px-4 py-3 rounded-xl transition-colors">Tentang Aplikasi</a>
                    <a href="{{ route('faq') }}" class="mobile-nav-link font-semibold text-sm text-[#15803D] px-4 py-3 rounded-xl transition-colors">Tanya Jawab (FAQ)</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-3xl mx-auto px-6 sm:px-8 py-16 relative z-10 w-full">
        
        <!-- Header -->
        <div class="text-center flex flex-col items-center gap-3 mb-16">
            <div class="w-12 h-12 rounded-2xl bg-[#DCFCE7] text-[#15803D] flex items-center justify-center text-xl shadow-sm font-bold">
                <i class="fa-solid fa-comments"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#15803D] mt-2">
                Tanya Jawab (FAQ)
            </h1>
            <p class="text-slate-550 max-w-md mx-auto text-sm leading-relaxed">
                Temukan jawaban cepat atas pertanyaan umum seputar cara kerja, laporan, dan validitas sistem siaga dan tanggap bencana TOLAK BENCANA.
            </p>
        </div>

        <!-- Accordion List -->
        <div class="flex flex-col gap-4">
            @forelse ($faqs as $faq)
                <div class="faq-item rounded-2xl p-5 cursor-pointer hover:border-gray-300" onclick="toggleFaq(this)">
                    <div class="flex items-center justify-between gap-4 select-none">
                        <span class="font-bold text-sm text-slate-800">{{ $faq->question }}</span>
                        <div class="w-7 h-7 rounded-full bg-slate-50 border border-gray-200 flex items-center justify-center text-[#15803D] shrink-0 transition-transform duration-300 faq-icon">
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </div>
                    </div>
                    <div class="faq-content">
                        <p class="text-xs text-slate-500 leading-relaxed text-justify">
                            {{ $faq->answer }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-white border border-gray-200 rounded-3xl">
                    <p class="text-slate-400 text-sm">Tidak ada tanya jawab yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>

    </main>

    <!-- Footer (Premium Dark Slate Style) -->
    <footer class="bg-[#0F172A] text-white py-16 px-6 sm:px-8 border-t border-slate-800">
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

    <!-- Accordion toggle script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
        });

        function toggleFaq(element) {
            const isActive = element.classList.contains('active');
            
            // Close other items
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
                const icon = item.querySelector('.faq-icon');
                if (icon) icon.style.transform = 'rotate(0deg)';
            });

            // Toggle selected item
            if (!isActive) {
                element.classList.add('active');
                const icon = element.querySelector('.faq-icon');
                if (icon) icon.style.transform = 'rotate(180deg)';
            }
        }
    </script>

</body>
</html>

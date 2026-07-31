<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Tanya Jawab (FAQ) Aplikasi KALIWEDI - Kanal Laporan Informasi Warga dan Evakuasi Dinamis Indramayu.">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <title>Tanya Jawab (FAQ) - KALIWEDI</title>

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
            background-color: #1e3a8a;
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

    <!-- Glow bubbles -->
    <div class="kw-glow-1 top-20 right-0"></div>
    <div class="kw-glow-2 top-[50vh] left-0"></div>

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
                        <a href="{{ route('about') }}" class="font-semibold text-sm text-slate-500 hover:text-[#1E3A8A] transition-colors pb-1">Tentang Aplikasi</a>
                    </li>
                    <li>
                        <a href="{{ route('faq') }}" class="font-semibold text-sm text-[#1E3A8A] transition-colors pb-1 border-b-2 border-[#1E3A8A]">Tanya Jawab (FAQ)</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-3xl mx-auto px-6 sm:px-8 py-16 relative z-10 w-full">
        
        <!-- Header -->
        <div class="text-center flex flex-col items-center gap-3 mb-16">
            <div class="w-12 h-12 rounded-2xl bg-[#E0F2F1] text-[#0F767E] flex items-center justify-center text-xl shadow-sm font-bold">
                <i class="fa-solid fa-comments"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#1E3A8A] mt-2">
                Tanya Jawab (FAQ)
            </h1>
            <p class="text-slate-550 max-w-md mx-auto text-sm leading-relaxed">
                Temukan jawaban cepat atas pertanyaan umum seputar cara kerja, laporan, dan validitas sistem siaga banjir KALIWEDI.
            </p>
        </div>

        <!-- Accordion List -->
        <div class="flex flex-col gap-4">
            @forelse ($faqs as $faq)
                <div class="faq-item rounded-2xl p-5 cursor-pointer hover:border-gray-300" onclick="toggleFaq(this)">
                    <div class="flex items-center justify-between gap-4 select-none">
                        <span class="font-bold text-sm text-slate-800">{{ $faq->question }}</span>
                        <div class="w-7 h-7 rounded-full bg-slate-50 border border-gray-200 flex items-center justify-center text-[#1E3A8A] shrink-0 transition-transform duration-300 faq-icon">
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

    <!-- Footer -->
    <footer class="bg-[#0F172A] text-white py-12 px-6 sm:px-8 border-t border-slate-800 mt-auto">
        <div class="max-w-3xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo KALIWEDI" class="w-8 h-8 rounded-lg bg-white p-0.5 object-contain">
                <span class="font-extrabold text-sm tracking-wider">KALIWEDI PKM-KC</span>
            </div>
            <span class="text-[10px] text-slate-500">&copy; 2026 Tim PKM-KC KALIWEDI. Politeknik Negeri Indramayu.</span>
        </div>
    </footer>

    <!-- Accordion toggle script -->
    <script>
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

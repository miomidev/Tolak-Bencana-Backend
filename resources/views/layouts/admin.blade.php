<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <title>@yield('title', 'Admin Dashboard') - KALIWEDI Admin</title>

    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Leaflet.js CSS (For map visualizations) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Style configuration -->
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>

    <!-- Vite Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F3F4F6] text-[#1F2937] h-screen overflow-hidden flex flex-col">

    <!-- Mobile Backdrop -->
    <div id="sidebar-backdrop" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-30 lg:hidden"></div>

    <div class="flex flex-1 min-h-0 overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 lg:static bg-gradient-to-b from-[#1E3A8A] via-[#1A2F74] to-[#14265E] text-white flex-shrink-0 flex flex-col shadow-2xl z-40">
            <!-- Sidebar Header -->
            <div class="sb-header h-16 flex items-center justify-between px-4 bg-white/5 border-b border-white/10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 min-w-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo KALIWEDI" class="w-9 h-9 rounded-lg bg-white p-1 object-contain shadow-md shrink-0">
                    <div class="sb-logo-text min-w-0">
                        <h1 class="font-extrabold text-lg leading-tight tracking-wider">KALIWEDI</h1>
                        <span class="text-[9px] text-[#E0F2F1]/80 uppercase tracking-widest font-semibold">Admin Panel</span>
                    </div>
                </a>
                <button type="button" id="sidebar-close-mobile" class="lg:hidden text-white/60 hover:text-white hover:bg-white/10 p-2 rounded-lg transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Sidebar Nav Links -->
            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto overflow-x-hidden">

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" title="Dashboard" class="group sb-link relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-[#0F767E] to-[#0E8C94] text-white shadow-lg shadow-[#0F767E]/30' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="sb-indicator absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-cyan-300 transition-opacity {{ request()->routeIs('admin.dashboard') ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                    <i class="sb-icon fa-solid fa-chart-line w-5 text-center text-lg"></i>
                    <span class="sb-text">Dashboard</span>
                </a>

                <!-- Pemantauan -->
                <div class="sb-section px-3 pt-5 pb-1.5">
                    <span class="text-[9px] uppercase tracking-widest text-white/35 font-bold">Pemantauan</span>
                </div>

                <a href="{{ route('admin.reports.index') }}" title="Laporan Banjir" class="group sb-link relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-gradient-to-r from-[#0F767E] to-[#0E8C94] text-white shadow-lg shadow-[#0F767E]/30' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="sb-indicator absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-cyan-300 transition-opacity {{ request()->routeIs('admin.reports.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                    <i class="sb-icon fa-solid fa-file-waveform w-5 text-center text-lg"></i>
                    <span class="sb-text">Laporan Banjir</span>
                    @if (($adminUnreadCount ?? 0) > 0)
                        <span class="sb-text ml-auto bg-[#F97316] text-white text-[10px] font-bold min-w-[18px] h-[18px] px-1.5 rounded-full flex items-center justify-center shadow-sm">{{ $adminUnreadCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.comments.index') }}" title="Komentar" class="group sb-link relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.comments.*') ? 'bg-gradient-to-r from-[#0F767E] to-[#0E8C94] text-white shadow-lg shadow-[#0F767E]/30' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="sb-indicator absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-cyan-300 transition-opacity {{ request()->routeIs('admin.comments.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                    <i class="sb-icon fa-solid fa-comment-dots w-5 text-center text-lg"></i>
                    <span class="sb-text">Komentar</span>
                </a>

                <a href="{{ route('admin.broadcast.create') }}" title="Broadcast Siaga" class="group sb-link relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.broadcast.*') ? 'bg-gradient-to-r from-[#0F767E] to-[#0E8C94] text-white shadow-lg shadow-[#0F767E]/30' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="sb-indicator absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-cyan-300 transition-opacity {{ request()->routeIs('admin.broadcast.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                    <i class="sb-icon fa-solid fa-bullhorn w-5 text-center text-lg"></i>
                    <span class="sb-text">Broadcast Siaga</span>
                </a>

                <!-- Master Data -->
                <div class="sb-section px-3 pt-5 pb-1.5">
                    <span class="text-[9px] uppercase tracking-widest text-white/35 font-bold">Master Data</span>
                </div>

                <a href="{{ route('admin.shelters.index') }}" title="Shelter Evakuasi" class="group sb-link relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.shelters.*') ? 'bg-gradient-to-r from-[#0F767E] to-[#0E8C94] text-white shadow-lg shadow-[#0F767E]/30' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="sb-indicator absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-cyan-300 transition-opacity {{ request()->routeIs('admin.shelters.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                    <i class="sb-icon fa-solid fa-warehouse w-5 text-center text-lg"></i>
                    <span class="sb-text">Shelter Evakuasi</span>
                </a>

                <a href="{{ route('admin.zones.index') }}" title="Zona Rawan Banjir" class="group sb-link relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.zones.*') ? 'bg-gradient-to-r from-[#0F767E] to-[#0E8C94] text-white shadow-lg shadow-[#0F767E]/30' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="sb-indicator absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-cyan-300 transition-opacity {{ request()->routeIs('admin.zones.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                    <i class="sb-icon fa-solid fa-circle-exclamation w-5 text-center text-lg"></i>
                    <span class="sb-text">Zona Rawan Banjir</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" title="Kategori Laporan" class="group sb-link relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-gradient-to-r from-[#0F767E] to-[#0E8C94] text-white shadow-lg shadow-[#0F767E]/30' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="sb-indicator absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-cyan-300 transition-opacity {{ request()->routeIs('admin.categories.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                    <i class="sb-icon fa-solid fa-tags w-5 text-center text-lg"></i>
                    <span class="sb-text">Kategori Laporan</span>
                </a>

                <a href="{{ route('admin.alert-levels.index') }}" title="Level Siaga" class="group sb-link relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.alert-levels.*') ? 'bg-gradient-to-r from-[#0F767E] to-[#0E8C94] text-white shadow-lg shadow-[#0F767E]/30' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="sb-indicator absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-cyan-300 transition-opacity {{ request()->routeIs('admin.alert-levels.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                    <i class="sb-icon fa-solid fa-gauge-high w-5 text-center text-lg"></i>
                    <span class="sb-text">Level Siaga</span>
                </a>

                <!-- Dukungan -->
                <div class="sb-section px-3 pt-5 pb-1.5">
                    <span class="text-[9px] uppercase tracking-widest text-white/35 font-bold">Dukungan</span>
                </div>

                <a href="{{ route('admin.contacts.index') }}" title="Kontak Darurat" class="group sb-link relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.contacts.*') ? 'bg-gradient-to-r from-[#0F767E] to-[#0E8C94] text-white shadow-lg shadow-[#0F767E]/30' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="sb-indicator absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-cyan-300 transition-opacity {{ request()->routeIs('admin.contacts.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                    <i class="sb-icon fa-solid fa-phone-flip w-5 text-center text-lg"></i>
                    <span class="sb-text">Kontak Darurat</span>
                </a>

                <a href="{{ route('admin.faqs.index') }}" title="FAQ" class="group sb-link relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.faqs.*') ? 'bg-gradient-to-r from-[#0F767E] to-[#0E8C94] text-white shadow-lg shadow-[#0F767E]/30' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="sb-indicator absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-cyan-300 transition-opacity {{ request()->routeIs('admin.faqs.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                    <i class="sb-icon fa-solid fa-circle-question w-5 text-center text-lg"></i>
                    <span class="sb-text">FAQ</span>
                </a>

                <a href="{{ route('admin.announcements.index') }}" title="Pemberitahuan" class="group sb-link relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.announcements.*') ? 'bg-gradient-to-r from-[#0F767E] to-[#0E8C94] text-white shadow-lg shadow-[#0F767E]/30' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="sb-indicator absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-cyan-300 transition-opacity {{ request()->routeIs('admin.announcements.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                    <i class="sb-icon fa-solid fa-bullhorn w-5 text-center text-lg"></i>
                    <span class="sb-text">Pemberitahuan</span>
                </a>

                <!-- Pengaturan -->
                <div class="sb-section px-3 pt-5 pb-1.5">
                    <span class="text-[9px] uppercase tracking-widest text-white/35 font-bold">Pengaturan</span>
                </div>

                <a href="{{ route('admin.users.index') }}" title="Pengguna" class="group sb-link relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-[#0F767E] to-[#0E8C94] text-white shadow-lg shadow-[#0F767E]/30' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="sb-indicator absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-cyan-300 transition-opacity {{ request()->routeIs('admin.users.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                    <i class="sb-icon fa-solid fa-users w-5 text-center text-lg"></i>
                    <span class="sb-text">Pengguna</span>
                </a>

                <a href="{{ route('admin.app-info.edit') }}" title="Konfigurasi Aplikasi" class="group sb-link relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.app-info.*') ? 'bg-gradient-to-r from-[#0F767E] to-[#0E8C94] text-white shadow-lg shadow-[#0F767E]/30' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="sb-indicator absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-cyan-300 transition-opacity {{ request()->routeIs('admin.app-info.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                    <i class="sb-icon fa-solid fa-gears w-5 text-center text-lg"></i>
                    <span class="sb-text">Konfigurasi Aplikasi</span>
                </a>

                <a href="{{ route('admin.audit-logs.index') }}" title="Audit Log" class="group sb-link relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.audit-logs.*') ? 'bg-gradient-to-r from-[#0F767E] to-[#0E8C94] text-white shadow-lg shadow-[#0F767E]/30' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="sb-indicator absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-cyan-300 transition-opacity {{ request()->routeIs('admin.audit-logs.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                    <i class="sb-icon fa-solid fa-clock-rotate-left w-5 text-center text-lg"></i>
                    <span class="sb-text">Audit Log</span>
                </a>

                <a href="{{ route('admin.profile.edit') }}" title="Profil Saya" class="group sb-link relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.profile.*') ? 'bg-gradient-to-r from-[#0F767E] to-[#0E8C94] text-white shadow-lg shadow-[#0F767E]/30' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="sb-indicator absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-cyan-300 transition-opacity {{ request()->routeIs('admin.profile.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                    <i class="sb-icon fa-solid fa-user-gear w-5 text-center text-lg"></i>
                    <span class="sb-text">Profil Saya</span>
                </a>
            </nav>

            <!-- Sidebar Footer User Profile Info -->
            <div class="sb-footer p-3 bg-white/5 border-t border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#0F767E] to-[#0E8C94] flex items-center justify-center text-white font-bold shrink-0 shadow-md">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="sb-profile-text min-w-0">
                        <p class="text-xs font-semibold truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-[#E0F2F1]/80 capitalize truncate">{{ Auth::user()->role }}</p>
                    </div>
                </div>
                <!-- Logout Trigger -->
                <form action="{{ route('admin.logout') }}" method="POST" class="sb-footer-logout inline">
                    @csrf
                    <button type="submit" title="Keluar" class="text-white/70 hover:text-red-400 p-2 hover:bg-white/10 rounded-lg transition-colors">
                        <i class="fa-solid fa-right-from-bracket text-base"></i>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">
            <!-- Header -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-8 shadow-sm z-10">
                <div class="flex items-center gap-3 md:gap-4">
                    <button type="button" id="sidebar-toggle" title="Buka/Tutup menu" class="text-[#1E3A8A] hover:bg-gray-100 p-2 rounded-lg transition-colors focus:outline-none">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <h2 class="text-base md:text-xl font-bold text-[#1E3A8A] truncate">@yield('header_title', 'Dashboard')</h2>
                </div>
                
                <div class="flex items-center gap-6">
                    <!-- Notification Icon -->
                    <div class="relative">
                        <button type="button" id="notif-btn" class="relative cursor-pointer hover:bg-gray-100 p-2 rounded-full transition-colors focus:outline-none">
                            <i class="fa-regular fa-bell text-xl text-gray-650"></i>
                            @if (($adminUnreadCount ?? 0) > 0)
                                <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-[#F97316] text-white text-[9px] font-bold rounded-full border border-white flex items-center justify-center">
                                    {{ $adminUnreadCount }}
                                </span>
                            @endif
                        </button>

                        <!-- Notification Dropdown -->
                        <div id="notif-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-2xl shadow-xl z-50 overflow-hidden">
                            <!-- Dropdown Header -->
                            <div class="px-5 py-3.5 border-b border-gray-100 bg-[#F9FAFB] flex items-center justify-between">
                                <span class="font-bold text-sm text-[#1E3A8A]">Notifikasi Laporan</span>
                                @if (($adminUnreadCount ?? 0) > 0)
                                    <span class="bg-[#F97316]/10 text-[#F97316] text-[10px] font-bold px-2 py-0.5 rounded-full">
                                        {{ $adminUnreadCount }} Pending
                                    </span>
                                @endif
                            </div>

                            <!-- Dropdown Body -->
                            <div class="max-h-[300px] overflow-y-auto divide-y divide-gray-100">
                                @forelse ($adminNotifications ?? [] as $notif)
                                    <a href="{{ route('admin.reports.show', $notif->id) }}" class="flex gap-3 px-5 py-3.5 hover:bg-[#E0F2F1]/30 transition-colors">
                                        <div class="w-8 h-8 rounded-full bg-[#1E3A8A]/10 text-[#1E3A8A] flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-droplet text-xs"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="font-bold text-xs text-slate-800 truncate">{{ $notif->user?->name ?? 'Warga' }}</span>
                                                <span class="text-[9px] text-gray-400 shrink-0 font-medium">{{ $notif->reported_at?->diffForHumans(null, true) }}</span>
                                            </div>
                                            <p class="text-[10px] text-gray-500 mt-0.5 truncate">{{ $notif->description }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <div class="py-8 text-center text-gray-400 text-xs flex flex-col items-center gap-2">
                                        <i class="fa-solid fa-circle-check text-2xl text-emerald-500"></i>
                                        <span>Semua laporan telah ditangani.</span>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Dropdown Footer -->
                            <div class="border-t border-gray-100 bg-slate-50/50">
                                <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}" class="block text-center py-2.5 text-xs font-bold text-[#0F767E] hover:text-[#1E3A8A] transition-colors">
                                    Lihat Semua Laporan Pending
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 min-h-0 overflow-y-auto p-8">
                <!-- Alert Flash Messages -->
                @if (session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3 text-emerald-800 text-sm">
                            <i class="fa-solid fa-circle-check text-lg text-emerald-500"></i>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-r-lg flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3 text-blue-800 text-sm">
                            <i class="fa-solid fa-circle-info text-lg text-blue-500"></i>
                            <span class="font-medium">{{ session('info') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-blue-500 hover:text-blue-700">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-lg flex items-center justify-between shadow-sm">
                        <div class="flex flex-col text-rose-800 text-sm gap-1">
                            @foreach ($errors->all() as $error)
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-circle-exclamation text-lg text-rose-500"></i>
                                    <span class="font-medium">{{ $error }}</span>
                                </div>
                            @endforeach
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Leaflet.js Library (For maps) -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    @yield('scripts')
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const notifBtn = document.getElementById('notif-btn');
            const notifDropdown = document.getElementById('notif-dropdown');

            if (notifBtn && notifDropdown) {
                notifBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    notifDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', function (e) {
                    if (!notifDropdown.contains(e.target) && !notifBtn.contains(e.target)) {
                        notifDropdown.classList.add('hidden');
                    }
                });
            }

            // ── Sidebar: desktop collapse + mobile off-canvas ─────────────
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const toggleBtn = document.getElementById('sidebar-toggle');
            const closeMobileBtn = document.getElementById('sidebar-close-mobile');

            const isMobile = () => window.innerWidth < 1024;

            // Restore desktop collapse state
            if (sidebar && !isMobile() && localStorage.getItem('kaliwedi_sidebar_collapsed') === '1') {
                document.body.classList.add('sidebar-collapsed');
            }

            function openMobileSidebar() {
                sidebar.classList.add('mobile-open');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeMobileSidebar() {
                sidebar.classList.remove('mobile-open');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (sidebar && toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    if (isMobile()) {
                        if (sidebar.classList.contains('mobile-open')) {
                            closeMobileSidebar();
                        } else {
                            openMobileSidebar();
                        }
                    } else {
                        document.body.classList.toggle('sidebar-collapsed');
                        localStorage.setItem(
                            'kaliwedi_sidebar_collapsed',
                            document.body.classList.contains('sidebar-collapsed') ? '1' : '0'
                        );
                    }
                });
            }

            if (sidebar && closeMobileBtn) {
                closeMobileBtn.addEventListener('click', closeMobileSidebar);
            }

            if (backdrop) {
                backdrop.addEventListener('click', closeMobileSidebar);
            }

            window.addEventListener('resize', function () {
                if (!isMobile()) {
                    closeMobileSidebar();
                }
            });
        });
    </script>
</body>
</html>

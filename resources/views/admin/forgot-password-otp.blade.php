<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Verifikasi OTP - KALIWEDI</title>

    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Vite Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-slate-100 to-teal-50 min-h-screen flex items-center justify-center p-6 sm:p-8 relative overflow-hidden">
    
    <!-- Floating background blur shapes -->
    <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-100/30 rounded-full blur-3xl -translate-y-16 translate-x-16"></div>
    <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-amber-100/20 rounded-full blur-3xl translate-y-16 -translate-x-16"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo and Brand Title -->
        <div class="flex flex-col items-center mb-8">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo KALIWEDI" class="w-16 h-16 rounded-2xl bg-white p-1.5 shadow-md object-contain hover:scale-105 transition-transform">
                <span class="font-extrabold text-3xl tracking-widest text-[#1E3A8A] mt-1">KALIWEDI</span>
                <span class="text-xs text-[#0F767E] uppercase tracking-widest font-extrabold -mt-1">PKM-KC Admin Console</span>
            </a>
        </div>

        <!-- OTP Verification Card -->
        <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-2xl border border-gray-150/80">
            <h2 class="text-xl font-bold text-[#1F2937] text-center mb-1">Verifikasi Kode OTP</h2>
            <p class="text-xs text-gray-400 text-center mb-6 font-normal">Masukkan 6 digit kode OTP yang telah dikirim ke alamat email <strong>{{ $email }}</strong>.</p>

            <!-- Dev/Simulation Help Block -->
            @if (session('simulated_otp'))
                <div class="mb-6 p-4 bg-[#E0F2F1] border-2 border-[#0F767E]/30 rounded-2xl text-center">
                    <span class="text-[10px] text-[#0F767E] uppercase font-bold tracking-widest block">Simulasi Pengiriman Email (Dev Mode)</span>
                    <span class="text-2xl font-black text-[#1E3A8A] block mt-1 tracking-[6px]">{{ session('simulated_otp') }}</span>
                    <span class="text-[9px] text-gray-450 block mt-1">Gunakan kode di atas untuk memverifikasi.</span>
                </div>
            @endif

            <!-- Errors Alert -->
            @if ($errors->any())
                <div class="mb-6 p-3.5 bg-rose-50 border-l-4 border-rose-500 rounded-r-lg text-rose-800 text-xs flex flex-col gap-1.5">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation text-sm text-rose-500"></i>
                            <span class="font-medium">{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('admin.forgot-password.otp.post') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                
                <!-- OTP Code Input -->
                <div class="flex flex-col gap-1.5">
                    <label for="otp" class="text-xs font-bold text-[#1F2937] uppercase tracking-wider text-center">Kode Verifikasi</label>
                    <div class="relative">
                        <input type="text" name="otp" id="otp" required maxlength="6" autofocus placeholder="0 0 0 0 0 0" 
                               class="w-full text-center tracking-[12px] text-2xl font-bold py-3.5 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-[#1E3A8A]/25 focus:border-[#1E3A8A] focus:outline-none transition-all">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-[#1E3A8A] hover:bg-[#172554] text-white font-bold py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg hover:scale-[1.01] mt-2 flex items-center justify-center gap-2">
                    <span>Verifikasi Kode</span>
                    <i class="fa-solid fa-shield-check"></i>
                </button>
            </form>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('admin.forgot-password') }}" class="text-xs font-semibold text-gray-500 hover:text-[#1E3A8A] flex items-center justify-center gap-1.5 hover:underline">
                <i class="fa-solid fa-circle-arrow-left"></i>
                <span>Kembali ke Langkah Sebelumnya</span>
            </a>
        </div>
    </div>
</body>
</html>

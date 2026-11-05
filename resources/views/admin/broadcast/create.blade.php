@extends('layouts.admin')

@section('title', 'Broadcast Siaga')
@section('header_title', 'Broadcast Siaga / Peringatan')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-150 bg-gray-50">
            <h4 class="font-extrabold text-base text-[#15803D]"><i class="fa-solid fa-bullhorn mr-2 text-[#F97316]"></i> Kirim Siaran Peringatan</h4>
            <p class="text-[11px] text-gray-400 font-normal mt-0.5">Pesan akan dikirim sebagai notifikasi ke semua pengguna aktif aplikasi Tolak Bencana.</p>
        </div>

        <form action="{{ route('admin.broadcast.send') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Judul Siaran</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#15803D]/40" placeholder="contoh: Siaga Tingkat 3 Banjir Rob">
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Isi Pesan</label>
                <textarea name="body" rows="5" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#15803D]/40" placeholder="Deskripsi kondisi, lokasi terdampak, dan arahan evakuasi...">{{ old('body') }}</textarea>
                @error('body') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Tipe Siaran</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <label class="cursor-pointer border rounded-xl p-4 flex items-start gap-3 transition-colors has-[:checked]:border-[#DC2626] has-[:checked]:bg-red-50">
                        <input type="radio" name="type" value="emergency_alert" class="mt-0.5 accent-[#DC2626]" {{ old('type', 'emergency_alert') === 'emergency_alert' ? 'checked' : '' }}>
                        <span>
                            <span class="block text-sm font-extrabold text-red-600"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Darurat</span>
                            <span class="block text-[10px] text-gray-500 mt-0.5">Peringatan darurat / sirene</span>
                        </span>
                    </label>
                    <label class="cursor-pointer border rounded-xl p-4 flex items-start gap-3 transition-colors has-[:checked]:border-[#F97316] has-[:checked]:bg-orange-50">
                        <input type="radio" name="type" value="flood_alert" class="mt-0.5 accent-[#F97316]" {{ old('type') === 'flood_alert' ? 'checked' : '' }}>
                        <span>
                            <span class="block text-sm font-extrabold text-orange-600"><i class="fa-solid fa-droplet mr-1"></i> Banjir</span>
                            <span class="block text-[10px] text-gray-500 mt-0.5">siaga bencana / rob</span>
                        </span>
                    </label>
                    <label class="cursor-pointer border rounded-xl p-4 flex items-start gap-3 transition-colors has-[:checked]:border-[#15803D] has-[:checked]:bg-[#DCFCE7]">
                        <input type="radio" name="type" value="info" class="mt-0.5 accent-[#15803D]" {{ old('type') === 'info' ? 'checked' : '' }}>
                        <span>
                            <span class="block text-sm font-extrabold text-[#15803D]"><i class="fa-solid fa-circle-info mr-1"></i> Informasi</span>
                            <span class="block text-[10px] text-gray-500 mt-0.5">Pemberitahuan umum</span>
                        </span>
                    </label>
                </div>
                @error('type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-[#F97316] hover:bg-[#EA580C] text-white text-sm font-bold px-6 py-2.5 rounded-xl transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Siaran
                </button>
                <span class="text-[11px] text-gray-400">Dikirim ke seluruh pengguna aktif aplikasi.</span>
            </div>
        </form>
    </div>
</div>
@endsection

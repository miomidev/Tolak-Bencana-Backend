@extends('layouts.admin')

@section('title', 'Konfigurasi Aplikasi')
@section('header_title', 'Konfigurasi Aplikasi')

@section('content')
<div class="mb-6">
    <div>
        <h3 class="text-lg font-extrabold text-[#1F2937]">Pengaturan Informasi Aplikasi</h3>
        <p class="text-sm text-gray-400">Data ini tampil di halaman Tentang / Profil aplikasi KALIWEDI</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8 max-w-4xl">
    <form action="{{ route('admin.app-info.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-bold text-gray-600 mb-1.5">Nama Aplikasi</label>
                <input type="text" name="app_name" value="{{ old('app_name', $appInfo->app_name) }}" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
                @error('app_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-600 mb-1.5">Developer</label>
                <input type="text" name="developer" value="{{ old('developer', $appInfo->developer) }}" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
                @error('developer') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-600 mb-1.5">Website</label>
                <input type="url" name="website" value="{{ old('website', $appInfo->website) }}" placeholder="https://..." class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
                @error('website') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-600 mb-1.5">Email Dukungan</label>
                <input type="email" name="support_email" value="{{ old('support_email', $appInfo->support_email) }}" placeholder="email@domain.com" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
                @error('support_email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-600 mb-1.5">URL Kebijakan Privasi</label>
                <input type="url" name="privacy_policy_url" value="{{ old('privacy_policy_url', $appInfo->privacy_policy_url) }}" placeholder="https://..." class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
                @error('privacy_policy_url') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-600 mb-1.5">URL Syarat & Ketentuan</label>
                <input type="url" name="terms_url" value="{{ old('terms_url', $appInfo->terms_url) }}" placeholder="https://..." class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
                @error('terms_url') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-5">
            <label class="block text-sm font-bold text-gray-600 mb-1.5">Deskripsi Aplikasi</label>
            <textarea name="description" rows="4" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40" placeholder="Deskripsi singkat aplikasi">{{ old('description', $appInfo->description) }}</textarea>
            @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3 pt-6">
            <button type="submit" class="bg-[#0F767E] hover:bg-[#0a5258] text-white text-sm font-bold px-6 py-2.5 rounded-xl shadow-sm transition-colors">
                <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Konfigurasi
            </button>
            <button type="reset" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-bold px-6 py-2.5 rounded-xl transition-colors">Reset Form</button>
        </div>
    </form>
</div>
@endsection

<div class="space-y-5">
    <div>
        <label class="block text-sm font-bold text-gray-600 mb-1.5">Kunci / Label <span class="text-red-500">*</span></label>
        <input type="text" name="key" value="{{ old('key', $announcement->key ?? '') }}" placeholder="Contoh: SIAGA ROB" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
        @error('key') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-600 mb-1.5">Isi Pemberitahuan <span class="text-red-500">*</span></label>
        <textarea name="message" rows="4" placeholder="Teks berjalan yang ingin ditampilkan..." class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">{{ old('message', $announcement->message ?? '') }}</textarea>
        @error('message') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-bold text-gray-600 mb-1.5">Urutan</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $announcement->sort_order ?? 0) }}" min="0" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
            @error('sort_order') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex items-end">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $announcement->is_active ?? true) ? 'checked' : '' }} class="accent-[#0F767E] w-5 h-5">
                <span class="text-sm text-gray-600">Aktif / tampil di landing page</span>
            </label>
        </div>
    </div>
</div>

<div class="flex gap-3 pt-6">
    <button type="submit" class="bg-[#0F767E] hover:bg-[#0a5258] text-white text-sm font-bold px-6 py-2.5 rounded-xl shadow-sm transition-colors">
        <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan
    </button>
    <a href="{{ route('admin.announcements.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-bold px-6 py-2.5 rounded-xl transition-colors">Batal</a>
</div>

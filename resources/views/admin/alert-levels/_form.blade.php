@php $level = $level ?? null; @endphp

<div class="space-y-5 max-w-md">
    <div>
        <label class="block text-sm font-bold text-gray-600 mb-1.5">Nama Level</label>
        <input type="text" name="name" value="{{ old('name', $level?->name) }}" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40" placeholder="contoh: Siaga">
        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-600 mb-1.5">Warna</label>
        <div class="flex items-center gap-3">
            <input type="color" name="color" value="{{ old('color', $level?->color ?? '#F97316') }}" class="w-14 h-11 border border-gray-200 rounded-xl cursor-pointer p-1">
            <input type="text" id="color-hex" value="{{ old('color', $level?->color ?? '#F97316') }}" class="flex-1 text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40" placeholder="#RRGGBB">
            <input type="hidden" name="color" id="color-hidden" value="{{ old('color', $level?->color ?? '#F97316') }}">
        </div>
        @error('color') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-bold text-gray-600 mb-1.5">Tinggi Minimal (cm)</label>
            <input type="number" name="min_height" value="{{ old('min_height', $level?->min_height) }}" min="0" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
            @error('min_height') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-600 mb-1.5">Tinggi Maksimal (cm)</label>
            <input type="number" name="max_height" value="{{ old('max_height', $level?->max_height) }}" min="0" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40" placeholder="kosongkan jika tanpa batas">
            @error('max_height') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>
    <p class="text-xs text-gray-400">Kosongkan tinggi maksimal untuk level tertinggi (tanpa batas atas).</p>

    <div>
        <label class="block text-sm font-bold text-gray-600 mb-1.5">Status</label>
        <select name="is_active" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F767E]/40">
            <option value="1" @selected((bool) old('is_active', $level?->is_active ?? true))>Aktif</option>
            <option value="0" @selected(!(bool) old('is_active', $level?->is_active ?? true))>Nonaktif</option>
        </select>
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="bg-[#0F767E] hover:bg-[#0a5258] text-white text-sm font-bold px-6 py-2.5 rounded-xl shadow-sm transition-colors">
            <i class="fa-solid fa-floppy-disk mr-1.5"></i> {{ isset($level) ? 'Simpan Perubahan' : 'Simpan Level' }}
        </button>
        <a href="{{ route('admin.alert-levels.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-bold px-6 py-2.5 rounded-xl transition-colors">Batal</a>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const colorPicker = document.querySelector('input[type="color"]');
        const hexInput = document.getElementById('color-hex');
        const hiddenInput = document.getElementById('color-hidden');

        function normalize(hex) {
            return /^#([0-9A-Fa-f]{6})$/.test(hex) ? hex.toUpperCase() : '#F97316';
        }

        colorPicker.addEventListener('input', function () {
            hexInput.value = this.value;
            hiddenInput.value = this.value;
        });

        hexInput.addEventListener('input', function () {
            const value = normalize(this.value);
            hiddenInput.value = value;
            colorPicker.value = value;
        });

        hexInput.addEventListener('blur', function () {
            this.value = normalize(this.value);
        });
    });
</script>
@endsection

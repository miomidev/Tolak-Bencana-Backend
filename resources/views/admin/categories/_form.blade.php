@php $category = $category ?? null; @endphp

<div class="space-y-5 max-w-md">
    <div>
        <label class="block text-sm font-bold text-gray-600 mb-1.5">Nama Kategori</label>
        <input type="text" name="name" value="{{ old('name', $category?->name) }}" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#15803D]/40" placeholder="contoh: Banjir Rob">
        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    @php
    $icons = [
        'droplet' => 'Droplet / Air',
        'house-tsunami' => 'House Tsunami / Banjir Rob',
        'cloud-showers-heavy' => 'Cloud Showers / Hujan Lebat',
        'water' => 'Water / Genangan Air',
        'house-crack' => 'House Crack / Rumah Rusak',
        'road-barrier' => 'Road Barrier / Jalan Terputus',
        'triangle-exclamation' => 'Triangle Exclamation / Bahaya',
        'truck-medical' => 'Truck Medical / Medis',
        'shield-halved' => 'Shield / Perlindungan',
        'person-running' => 'Person Running / Evakuasi',
        'warehouse' => 'Warehouse / Shelter',
        'phone' => 'Phone / Telepon',
        'cloud-bolt' => 'Cloud Bolt / Badai Petir',
        'wind' => 'Wind / Angin Kencang',
        'tree' => 'Tree / Pohon Tumbang',
        'bridge-water' => 'Bridge / Jembatan Meluap',
    ];
    @endphp

    <div>
        <label class="block text-sm font-bold text-gray-600 mb-2">Pilih Ikon Kategori</label>
        <input type="hidden" name="icon" id="selected-icon" value="{{ old('icon', $category?->icon) }}">
        
        <div class="grid grid-cols-4 gap-3">
            @foreach($icons as $value => $label)
                @php
                    $cleanLabel = explode(' / ', $label)[1] ?? $label;
                    $isSelected = old('icon', $category?->icon) == $value;
                @endphp
                <button type="button" 
                        onclick="selectIcon('{{ $value }}')" 
                        id="icon-btn-{{ $value }}"
                        class="flex flex-col items-center justify-center p-3 rounded-xl border text-center transition-all duration-150 hover:bg-slate-50 icon-selector-btn {{ $isSelected ? 'border-[#15803D] bg-[#DCFCE7] text-[#15803D] ring-2 ring-[#15803D]/30 font-bold' : 'border-gray-200 text-gray-500 hover:text-slate-700' }}"
                        title="{{ $label }}">
                    <i class="fa-solid fa-{{ $value }} text-lg"></i>
                    <span class="text-[9px] mt-1.5 font-medium truncate w-full">{{ $cleanLabel }}</span>
                </button>
            @endforeach
        </div>
        @error('icon') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <script>
        function selectIcon(iconValue) {
            // Set hidden input value
            document.getElementById('selected-icon').value = iconValue;

            // Reset style of all buttons
            document.querySelectorAll('.icon-selector-btn').forEach(btn => {
                btn.classList.remove('border-[#15803D]', 'bg-[#DCFCE7]', 'text-[#15803D]', 'ring-2', 'ring-[#15803D]/30', 'font-bold');
                btn.classList.add('border-gray-200', 'text-gray-500', 'hover:text-slate-700');
            });

            // Highlight selected button
            const selectedBtn = document.getElementById('icon-btn-' + iconValue);
            if (selectedBtn) {
                selectedBtn.classList.remove('border-gray-200', 'text-gray-500', 'hover:text-slate-700');
                selectedBtn.classList.add('border-[#15803D]', 'bg-[#DCFCE7]', 'text-[#15803D]', 'ring-2', 'ring-[#15803D]/30', 'font-bold');
            }
        }
    </script>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-bold text-gray-600 mb-1.5">Urutan</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $category?->sort_order) }}" min="0" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#15803D]/40">
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-600 mb-1.5">Status</label>
            <select name="is_active" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#15803D]/40">
                <option value="1" @selected((bool) old('is_active', $category?->is_active ?? true))>Aktif</option>
                <option value="0" @selected(!(bool) old('is_active', $category?->is_active ?? true))>Nonaktif</option>
            </select>
        </div>
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="bg-[#15803D] hover:bg-[#0a5258] text-white text-sm font-bold px-6 py-2.5 rounded-xl shadow-sm transition-colors">
            <i class="fa-solid fa-floppy-disk mr-1.5"></i> {{ isset($category) ? 'Simpan Perubahan' : 'Simpan Kategori' }}
        </button>
        <a href="{{ route('admin.categories.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-bold px-6 py-2.5 rounded-xl transition-colors">Batal</a>
    </div>
</div>

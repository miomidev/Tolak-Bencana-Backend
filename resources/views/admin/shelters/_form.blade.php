@php
    $shelter = $shelter ?? null;
    $lat = old('latitude', $shelter?->latitude ?? -6.35);
    $lng = old('longitude', $shelter?->longitude ?? 108.25);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Form fields -->
    <div class="space-y-5">
        <div>
            <label class="block text-sm font-bold text-gray-600 mb-1.5">Nama Shelter</label>
            <input type="text" name="name" value="{{ old('name', $shelter?->name) }}" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#15803D]/40" placeholder="contoh: Balai Desa Sukra">
            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-600 mb-1.5">Alamat</label>
            <textarea name="address" rows="3" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#15803D]/40" placeholder="Alamat lengkap lokasi shelter">{{ old('address', $shelter?->address) }}</textarea>
            @error('address') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-600 mb-1.5">Kapasitas (orang)</label>
                <input type="number" name="capacity" value="{{ old('capacity', $shelter?->capacity) }}" min="0" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#15803D]/40">
                @error('capacity') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-600 mb-1.5">Terisi saat ini</label>
                <input type="number" name="current_occupancy" value="{{ old('current_occupancy', $shelter?->current_occupancy) }}" min="0" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#15803D]/40">
                @error('current_occupancy') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-600 mb-1.5">Status</label>
                <select name="status" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#15803D]/40">
                    <option value="active" @selected(old('status', $shelter?->status) === 'active')>Aktif</option>
                    <option value="inactive" @selected(old('status', $shelter?->status) === 'inactive')>Nonaktif</option>
                    <option value="full" @selected(old('status', $shelter?->status) === 'full')>Penuh</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-600 mb-1.5">Kontak</label>
                <input type="text" name="contact" value="{{ old('contact', $shelter?->contact) }}" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#15803D]/40" placeholder="No. telepon pengelola">
            </div>
        </div>

        <!-- Coordinates -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-600 mb-1.5">Latitude</label>
                <input type="text" name="latitude" id="latitude" value="{{ $lat }}" readonly class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-600 mb-1.5">Longitude</label>
                <input type="text" name="longitude" id="longitude" value="{{ $lng }}" readonly class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5">
            </div>
        </div>
        @error('latitude') <p class="text-xs text-red-500 -mt-3">{{ $message }}</p> @enderror

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-[#15803D] hover:bg-[#0a5258] text-white text-sm font-bold px-6 py-2.5 rounded-xl shadow-sm transition-colors">
                <i class="fa-solid fa-floppy-disk mr-1.5"></i> {{ isset($shelter) ? 'Simpan Perubahan' : 'Simpan Shelter' }}
            </button>
            <a href="{{ route('admin.shelters.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-bold px-6 py-2.5 rounded-xl transition-colors">Batal</a>
        </div>
    </div>

    <!-- Map picker -->
    <div>
        <label class="block text-sm font-bold text-gray-600 mb-1.5">Pilih Lokasi di Peta</label>
        <div id="shelter-map" class="w-full h-[420px] rounded-2xl border border-gray-200 shadow-sm z-0"></div>
        <p class="text-xs text-gray-400 mt-2"><i class="fa-solid fa-hand-pointer mr-1"></i> Klik pada peta untuk menandai lokasi shelter.</p>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        const startLat = parseFloat(latInput.value) || -6.35;
        const startLng = parseFloat(lngInput.value) || 108.25;

        const map = L.map('shelter-map').setView([startLat, startLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let marker = L.marker([startLat, startLng], { draggable: true }).addTo(map);

        function syncMarker(latlng) {
            marker.setLatLng(latlng);
            latInput.value = latlng.lat.toFixed(6);
            lngInput.value = latlng.lng.toFixed(6);
        }

        map.on('click', function (e) {
            syncMarker(e.latlng);
        });

        marker.on('dragend', function (e) {
            syncMarker(e.target.getLatLng());
        });
    });
</script>
@endsection

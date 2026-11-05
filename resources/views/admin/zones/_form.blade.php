@php
    $zone = $zone ?? null;
    $polygon = old('polygon', $zone?->polygon);
    $riskLevel = old('risk_level', $zone?->risk_level ?? 'medium');
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Form fields -->
    <div class="space-y-5">
        <div>
            <label class="block text-sm font-bold text-gray-600 mb-1.5">Nama Zona</label>
            <input type="text" name="name" value="{{ old('name', $zone?->name) }}" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#15803D]/40" placeholder="contoh: Zona Rawan Sukra Wetan">
            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-600 mb-1.5">Tingkat Risiko</label>
            <select name="risk_level" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#15803D]/40">
                <option value="low" @selected($riskLevel === 'low')>Rendah</option>
                <option value="medium" @selected($riskLevel === 'medium')>Sedang</option>
                <option value="high" @selected($riskLevel === 'high')>Tinggi</option>
                <option value="critical" @selected($riskLevel === 'critical')>Kritis</option>
            </select>
            @error('risk_level') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-600 mb-1.5">Deskripsi</label>
            <textarea name="description" rows="5" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#15803D]/40" placeholder="Informasi tambahan zona ini">{{ old('description', $zone?->description) }}</textarea>
            @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <input type="hidden" name="polygon" id="polygon" value="{{ $polygon }}">
        @error('polygon') <p class="text-xs text-red-500">{{ $message }}</p> @enderror

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-[#15803D] hover:bg-[#0a5258] text-white text-sm font-bold px-6 py-2.5 rounded-xl shadow-sm transition-colors">
                <i class="fa-solid fa-floppy-disk mr-1.5"></i> {{ isset($zone) ? 'Simpan Perubahan' : 'Simpan Zona' }}
            </button>
            <a href="{{ route('admin.zones.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-bold px-6 py-2.5 rounded-xl transition-colors">Batal</a>
        </div>
    </div>

    <!-- Map drawing -->
    <div>
        <div class="flex items-center justify-between mb-1.5">
            <label class="block text-sm font-bold text-gray-600">Gambar Area Zona</label>
            <button type="button" id="clear-zone" class="text-xs font-bold text-red-500 hover:text-red-700">Hapus Area</button>
        </div>
        <div id="zone-map" class="w-full h-[420px] rounded-2xl border border-gray-200 shadow-sm z-0"></div>
        <p class="text-xs text-gray-400 mt-2"><i class="fa-solid fa-draw-polygon mr-1"></i> Gunakan ikon polygon untuk menggambar area. Klik titik pada peta, lalu klik titik pertama untuk menutup area.</p>
    </div>
</div>

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const polygonInput = document.getElementById('polygon');
        const map = L.map('zone-map').setView([-6.35, 108.25], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const drawLayer = L.featureGroup().addTo(map);

        const riskColors = {
            low: '#22C55E',
            medium: '#FACC15',
            high: '#F97316',
            critical: '#DC2626'
        };

        function riskColor() {
            const select = document.querySelector('select[name="risk_level"]');
            return riskColors[select ? select.value : 'medium'] || '#F97316';
        }

        const editableLayers = new L.FeatureGroup();
        map.addLayer(editableLayers);

        const drawControl = new L.Control.Draw({
            draw: {
                polygon: {
                    allowIntersection: false,
                    showArea: true,
                    shapeOptions: { color: riskColor() }
                },
                polyline: false,
                rectangle: false,
                circle: false,
                circlemarker: false,
                marker: false
            },
            edit: { featureGroup: editableLayers }
        });
        map.addControl(drawControl);

        // Load existing polygon (GeoJSON string)
        const existing = polygonInput.value;
        if (existing) {
            try {
                const geoJson = JSON.parse(existing);
                L.geoJSON(geoJson, {
                    style: () => ({ color: riskColor(), weight: 3, fillOpacity: 0.2 })
                }).addTo(editableLayers);
                map.fitBounds(editableLayers.getBounds());
            } catch (e) {
                console.error('Polygon data tidak valid', e);
            }
        }

        function toGeoJson() {
            const geojson = editableLayers.toGeoJSON();
            if (!geojson.features || geojson.features.length === 0) {
                return '';
            }
            return JSON.stringify(geojson);
        }

        map.on('draw:created', function (e) {
            const layer = e.layer;
            editableLayers.addLayer(layer);
            polygonInput.value = toGeoJson();
        });

        map.on('draw:edited', function () {
            polygonInput.value = toGeoJson();
        });

        map.on('draw:deleted', function () {
            polygonInput.value = toGeoJson();
        });

        document.getElementById('clear-zone').addEventListener('click', function () {
            editableLayers.clearLayers();
            polygonInput.value = '';
        });
    });
</script>
@endsection

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DisasterZone;
use Illuminate\Http\Request;

class DisasterZoneController extends Controller
{
    public function index(Request $request)
    {
        $zones = DisasterZone::query()
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%");
            })
            ->when($request->filled('risk'), function ($q) use ($request) {
                $q->where('risk_level', $request->risk);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.zones.index', compact('zones'));
    }

    public function create()
    {
        return view('admin.zones.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        DisasterZone::create($data);

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zona "'.$data['name'].'" berhasil ditambahkan.');
    }

    public function edit(DisasterZone $zone)
    {
        return view('admin.zones.edit', compact('zone'));
    }

    public function update(Request $request, DisasterZone $zone)
    {
        $data = $this->validateData($request);

        $zone->update($data);

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zona "'.$data['name'].'" berhasil diperbarui.');
    }

    public function destroy(DisasterZone $zone)
    {
        $name = $zone->name;
        $zone->delete();

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zona "'.$name.'" berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'polygon' => 'required|json',
            'risk_level' => 'required|in:low,medium,high,critical',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama zona wajib diisi.',
            'polygon.required' => 'Gambar area zona pada peta terlebih dahulu.',
            'polygon.json' => 'Data area zona tidak valid.',
            'risk_level.required' => 'Tingkat risiko wajib diisi.',
            'risk_level.in' => 'Tingkat risiko tidak valid.',
        ]);
    }
}

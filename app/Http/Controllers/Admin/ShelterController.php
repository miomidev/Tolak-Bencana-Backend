<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shelter;
use Illuminate\Http\Request;

class ShelterController extends Controller
{
    public function index(Request $request)
    {
        $shelters = Shelter::query()
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->q}%")
                        ->orWhere('address', 'like', "%{$request->q}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.shelters.index', compact('shelters'));
    }

    public function create()
    {
        return view('admin.shelters.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Shelter::create($data);

        return redirect()->route('admin.shelters.index')
            ->with('success', 'Shelter "'.$data['name'].'" berhasil ditambahkan.');
    }

    public function edit(Shelter $shelter)
    {
        return view('admin.shelters.edit', compact('shelter'));
    }

    public function update(Request $request, Shelter $shelter)
    {
        $data = $this->validateData($request);

        $shelter->update($data);

        return redirect()->route('admin.shelters.index')
            ->with('success', 'Shelter "'.$data['name'].'" berhasil diperbarui.');
    }

    public function destroy(Shelter $shelter)
    {
        $name = $shelter->name;
        $shelter->delete();

        return redirect()->route('admin.shelters.index')
            ->with('success', 'Shelter "'.$name.'" berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'required|string',
            'capacity' => 'required|integer|min:0',
            'current_occupancy' => 'required|integer|min:0|lte:capacity',
            'status' => 'required|in:active,inactive,full',
            'contact' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama shelter wajib diisi.',
            'latitude.required' => 'Koordinat latitude wajib diisi (klik pada peta).',
            'longitude.required' => 'Koordinat longitude wajib diisi (klik pada peta).',
            'address.required' => 'Alamat wajib diisi.',
            'capacity.required' => 'Kapasitas wajib diisi.',
            'current_occupancy.lte' => 'Jumlah terisi tidak boleh melebihi kapasitas.',
            'status.in' => 'Status shelter tidak valid.',
        ]);
    }
}

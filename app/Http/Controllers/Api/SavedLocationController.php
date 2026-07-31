<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SavedLocation;
use Illuminate\Http\Request;

class SavedLocationController extends Controller
{
    /**
     * Display a listing of the saved locations.
     */
    public function index()
    {
        $locations = SavedLocation::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $locations,
        ]);
    }

    /**
     * Store a newly created saved location in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'place_name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'icon_type' => 'nullable|string|in:home,work,school,other',
        ]);

        // Default icon_type based on place name if not provided
        $iconType = $request->input('icon_type');
        if (! $iconType) {
            $nameLower = strtolower($request->input('place_name'));
            if (str_contains($nameLower, 'rumah') || str_contains($nameLower, 'home') || str_contains($nameLower, 'kost')) {
                $iconType = 'home';
            } elseif (str_contains($nameLower, 'kantor') || str_contains($nameLower, 'kerja') || str_contains($nameLower, 'office') || str_contains($nameLower, 'work')) {
                $iconType = 'work';
            } elseif (str_contains($nameLower, 'sekolah') || str_contains($nameLower, 'kampus') || str_contains($nameLower, 'school')) {
                $iconType = 'school';
            } else {
                $iconType = 'other';
            }
        }

        $location = SavedLocation::create([
            'user_id' => auth()->id(),
            'place_name' => $request->input('place_name'),
            'address' => $request->input('address'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'icon_type' => $iconType,
            'is_primary' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil disimpan!',
            'data' => $location,
        ], 201);
    }

    /**
     * Update the specified saved location in storage.
     */
    public function update(Request $request, $id)
    {
        $location = SavedLocation::where('user_id', auth()->id())->find($id);

        if (! $location) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak ditemukan!',
            ], 404);
        }

        $request->validate([
            'place_name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'icon_type' => 'nullable|string|in:home,work,school,other',
        ]);

        // Default icon_type based on place name if not provided
        $iconType = $request->input('icon_type');
        if (! $iconType) {
            $nameLower = strtolower($request->input('place_name'));
            if (str_contains($nameLower, 'rumah') || str_contains($nameLower, 'home') || str_contains($nameLower, 'kost')) {
                $iconType = 'home';
            } elseif (str_contains($nameLower, 'kantor') || str_contains($nameLower, 'kerja') || str_contains($nameLower, 'office') || str_contains($nameLower, 'work')) {
                $iconType = 'work';
            } elseif (str_contains($nameLower, 'sekolah') || str_contains($nameLower, 'kampus') || str_contains($nameLower, 'school')) {
                $iconType = 'school';
            } else {
                $iconType = 'other';
            }
        }

        $location->update([
            'place_name' => $request->input('place_name'),
            'address' => $request->input('address'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'icon_type' => $iconType,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil diperbarui!',
            'data' => $location,
        ]);
    }

    /**
     * Remove the specified saved location from storage.
     */
    public function destroy($id)
    {
        $location = SavedLocation::where('user_id', auth()->id())->find($id);

        if (! $location) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak ditemukan!',
            ], 404);
        }

        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil dihapus!',
        ]);
    }
}

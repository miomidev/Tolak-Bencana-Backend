<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlertLevel;
use Illuminate\Http\Request;

class AlertLevelController extends Controller
{
    public function index()
    {
        $levels = AlertLevel::orderBy('min_height')->paginate(10);

        return view('admin.alert-levels.index', compact('levels'));
    }

    public function create()
    {
        return view('admin.alert-levels.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        AlertLevel::create($data);

        return redirect()->route('admin.alert-levels.index')
            ->with('success', 'Level siaga "'.$data['name'].'" berhasil ditambahkan.');
    }

    public function edit(AlertLevel $level)
    {
        return view('admin.alert-levels.edit', compact('level'));
    }

    public function update(Request $request, AlertLevel $level)
    {
        $data = $this->validateData($request, $level->id);

        $level->update($data);

        return redirect()->route('admin.alert-levels.index')
            ->with('success', 'Level siaga "'.$data['name'].'" berhasil diperbarui.');
    }

    public function destroy(AlertLevel $level)
    {
        $name = $level->name;
        $level->delete();

        return redirect()->route('admin.alert-levels.index')
            ->with('success', 'Level siaga "'.$name.'" berhasil dihapus.');
    }

    public function toggle(AlertLevel $level)
    {
        $level->is_active = ! $level->is_active;
        $level->save();

        return redirect()->route('admin.alert-levels.index')
            ->with('success', 'Level siaga "'.$level->name.'" '.($level->is_active ? 'diaktifkan' : 'dinonaktifkan').'.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'min_height' => 'required|integer|min:0',
            'max_height' => 'nullable|integer|gte:min_height',
            'is_active' => 'sometimes|boolean',
        ], [
            'name.required' => 'Nama level siaga wajib diisi.',
            'color.required' => 'Warna wajib diisi.',
            'min_height.required' => 'Tinggi minimal wajib diisi.',
            'max_height.gte' => 'Tinggi maksimal harus lebih besar atau sama dengan tinggi minimal.',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportCategoryController extends Controller
{
    public function index()
    {
        $categories = ReportCategory::orderBy('sort_order')->orderBy('id')->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['sort_order'] = $data['sort_order'] ?? (ReportCategory::max('sort_order') + 1);

        ReportCategory::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori "'.$data['name'].'" berhasil ditambahkan.');
    }

    public function edit(ReportCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, ReportCategory $category)
    {
        $data = $this->validateData($request, $category->id);

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori "'.$data['name'].'" berhasil diperbarui.');
    }

    public function destroy(ReportCategory $category)
    {
        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori "'.$name.'" berhasil dihapus.');
    }

    public function toggle(ReportCategory $category)
    {
        $category->is_active = ! $category->is_active;
        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori "'.$category->name.'" '.($category->is_active ? 'diaktifkan' : 'dinonaktifkan').'.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('report_categories', 'name')->ignore($ignoreId)],
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Kategori dengan nama tersebut sudah ada.',
        ]);
    }
}

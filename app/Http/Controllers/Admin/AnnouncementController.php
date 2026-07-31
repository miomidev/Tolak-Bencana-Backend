<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Support\Audit;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['sort_order'] = $data['sort_order'] ?? (Announcement::max('sort_order') + 1);

        $announcement = Announcement::create($data);

        Audit::log('announcement.created', 'Pemberitahuan "'.$data['key'].'" berhasil dibuat.', $announcement);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pemberitahuan berhasil ditambahkan.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $this->validateData($request);

        $announcement->update($data);

        Audit::log('announcement.updated', 'Pemberitahuan "'.$data['key'].'" diperbarui.', $announcement);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pemberitahuan berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        Audit::log('announcement.deleted', 'Pemberitahuan "'.$announcement->key.'" dihapus.', $announcement);

        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pemberitahuan berhasil dihapus.');
    }

    public function toggle(Announcement $announcement)
    {
        $announcement->is_active = ! $announcement->is_active;
        $announcement->save();

        Audit::log(
            $announcement->is_active ? 'announcement.activated' : 'announcement.deactivated',
            'Pemberitahuan "'.$announcement->key.'" '.($announcement->is_active ? 'diaktifkan' : 'dinonaktifkan').'.',
            $announcement
        );

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pemberitahuan '.($announcement->is_active ? 'diaktifkan' : 'dinonaktifkan').'.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'key' => 'required|string|max:100',
            'message' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ], [
            'key.required' => 'Kunci pemberitahuan wajib diisi.',
            'message.required' => 'Isi pemberitahuan wajib diisi.',
        ]);
    }
}

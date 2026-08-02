<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\User;
use App\Support\Audit;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    public function create()
    {
        return view('admin.broadcast.create');
    }

    public function history(Request $request)
    {
        $query = Notification::with('user')
            ->orderBy('sent_at', 'desc');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('sent_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('sent_at', '<=', $request->date_to);
        }

        // Get unique broadcasts (group by title + body + sent_at minute)
        // We show one row per broadcast "batch" (same title+body sent at same time)
        $broadcasts = $query->select('title', 'body', 'type', 'sent_at')
            ->selectRaw('COUNT(*) as recipient_count')
            ->selectRaw('SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) as read_count')
            ->groupBy('title', 'body', 'type', 'sent_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.broadcast.history', compact('broadcasts'));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:150',
            'body' => 'required|string|max:1000',
            'type' => 'required|in:emergency_alert,flood_alert,info',
        ], [
            'title.required' => 'Judul wajib diisi.',
            'body.required' => 'Isi pesan wajib diisi.',
            'type.in' => 'Tipe siaran tidak valid.',
        ]);

        $now = now();
        $sent = 0;

        User::where('is_active', true)->get()->each(function (User $user) use ($data, $now, &$sent) {
            $setting = NotificationSetting::where('user_id', $user->id)->first();
            $pref = true;

            if ($setting && in_array($data['type'], ['flood_alert', 'emergency_alert'])) {
                $pref = (bool) $setting->{$data['type']};
            }

            if (! $pref) {
                return;
            }

            Notification::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'body' => $data['body'],
                'type' => $data['type'],
                'is_read' => false,
                'sent_at' => $now,
            ]);

            $sent++;
        });

        Audit::log('broadcast.sent', 'Siaran "'.$data['title'].'" ('.($data['type']).') dikirim ke '.$sent.' pengguna.');

        return redirect()->route('admin.broadcast.history')
            ->with('success', 'Siaran berhasil dikirim ke '.$sent.' pengguna aktif.');
    }
}

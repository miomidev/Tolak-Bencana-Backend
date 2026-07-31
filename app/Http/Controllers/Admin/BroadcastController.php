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

        return redirect()->route('admin.broadcast.create')
            ->with('success', 'Siaran berhasil dikirim ke '.$sent.' pengguna aktif.');
    }
}

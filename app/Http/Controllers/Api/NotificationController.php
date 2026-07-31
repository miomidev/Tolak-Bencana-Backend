<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get the list of notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('sent_at', 'desc')
            ->get();

        $formattedNotifications = $notifications->map(function ($notif) {
            return [
                'id' => $notif->id,
                'user_id' => $notif->user_id,
                'title' => $notif->title,
                'body' => $notif->body,
                'type' => $notif->type,
                'is_read' => (bool) $notif->is_read,
                'sent_at' => $notif->sent_at->toIso8601String(),
                'time_ago' => $notif->sent_at->diffForHumans(),
            ];
        });

        return response()->json([
            'notifications' => $formattedNotifications,
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();

        $notification = Notification::where('user_id', $user->id)
            ->findOrFail($id);

        $notification->is_read = true;
        $notification->save();

        return response()->json([
            'message' => 'Notifikasi telah dibaca',
            'notification' => [
                'id' => $notification->id,
                'is_read' => true,
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Shelter;
use Illuminate\Http\Request;

class MapDataController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $reportsQuery = Report::with(['user:id,name,username,avatar_url'])
            ->withCount(['likes', 'activeComments'])
            ->orderBy('reported_at', 'desc');

        if ($user) {
            $reportsQuery->withExists(['likes as is_liked' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }]);
        }

        $reports = $reportsQuery->get()->map(function ($report) {
            return [
                'id' => $report->id,
                'latitude' => $report->latitude,
                'longitude' => $report->longitude,
                'address' => $report->address,
                'description' => $report->description,
                'photo_url' => $report->photo_url,
                'severity' => $report->severity,
                'status' => $report->status,
                'category' => $report->category ?? 'Bencana',
                'reported_at' => $report->reported_at->toIso8601String(),
                'time_ago' => $report->reported_at->diffForHumans(),
                'likes_count' => $report->likes_count ?? 0,
                'comments_count' => $report->active_comments_count ?? 0,
                'is_liked' => (bool) ($report->is_liked ?? false),
                'user' => $report->user,
            ];
        });

        $savedLocations = $user?->savedLocations()
            ->orderBy('place_name')
            ->get() ?? [];

        $shelters = Shelter::orderBy('name')->get();

        return response()->json([
            'reports' => $reports,
            'saved_locations' => $savedLocations,
            'shelters' => $shelters,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard stats and reports list.
     */
    public function index(Request $request)
    {
        $user = $request->user('sanctum');

        // 1. Calculate statistics
        $totalReports = Report::count();
        $activeReports = Report::whereIn('status', ['pending', 'verified'])->count();
        $verifiedReports = Report::where('status', 'verified')->count();

        $percentageVerified = $totalReports > 0
            ? round(($verifiedReports / $totalReports) * 100)
            : 0;

        // 2. Fetch reports with counts and is_liked status
        $reportsQuery = Report::with(['user:id,name,username,avatar_url'])
            ->withCount(['likes', 'activeComments'])
            ->orderBy('reported_at', 'desc');

        if ($user) {
            $reportsQuery->withExists(['likes as is_liked' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }]);
        }

        $reports = $reportsQuery->get();

        // Standardize the output format
        $formattedReports = $reports->map(function ($report) {
            return [
                'id' => $report->id,
                'user_id' => $report->user_id,
                'latitude' => $report->latitude,
                'longitude' => $report->longitude,
                'address' => $report->address,
                'description' => $report->description,
                'photo_url' => $report->photo_url,
                'severity' => $report->severity,
                'status' => $report->status,
                'category' => $report->category ?? 'Bencana', // default category if null
                'reported_at' => $report->reported_at->toIso8601String(),
                'time_ago' => $report->reported_at->diffForHumans(),
                'likes_count' => $report->likes_count ?? 0,
                'comments_count' => $report->active_comments_count ?? 0,
                'is_liked' => (bool) ($report->is_liked ?? false),
                'user' => $report->user,
            ];
        });

        return response()->json([
            'stats' => [
                'laporan_aktif' => $activeReports,
                'total_laporan' => $totalReports,
                'persentase_terverifikasi' => $percentageVerified,
            ],
            'reports' => $formattedReports,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportLike;
use Illuminate\Http\Request;

class ReportLikeController extends Controller
{
    /**
     * Toggle like/unlike status for a report.
     */
    public function toggleLike(Request $request, $id)
    {
        $user = $request->user();
        $report = Report::findOrFail($id);

        $existingLike = ReportLike::where('user_id', $user->id)
            ->where('report_id', $report->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $isLiked = false;
        } else {
            ReportLike::create([
                'user_id' => $user->id,
                'report_id' => $report->id,
            ]);
            $isLiked = true;
        }

        $likesCount = ReportLike::where('report_id', $report->id)->count();

        return response()->json([
            'message' => $isLiked ? 'Laporan disukai' : 'Batal menyukai laporan',
            'is_liked' => $isLiked,
            'likes_count' => $likesCount,
        ]);
    }
}

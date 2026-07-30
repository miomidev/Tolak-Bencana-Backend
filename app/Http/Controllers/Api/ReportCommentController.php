<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportComment;
use Illuminate\Http\Request;

class ReportCommentController extends Controller
{
    /**
     * Display a listing of comments for a specific report.
     */
    public function index($reportId)
    {
        $report = Report::findOrFail($reportId);

        $comments = ReportComment::where('report_id', $report->id)
            ->with('user:id,name,username,avatar_url')
            ->orderBy('created_at', 'asc')
            ->get();

        $formattedComments = $comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'user_id' => $comment->user_id,
                'report_id' => $comment->report_id,
                'comment' => $comment->comment,
                'created_at' => $comment->created_at->toIso8601String(),
                'time_ago' => $comment->created_at->diffForHumans(),
                'user' => $comment->user,
            ];
        });

        return response()->json([
            'comments' => $formattedComments,
        ]);
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, $reportId)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $user = $request->user();
        $report = Report::findOrFail($reportId);

        $comment = ReportComment::create([
            'user_id' => $user->id,
            'report_id' => $report->id,
            'comment' => $request->comment,
        ]);

        // Load user info for response
        $comment->load('user:id,name,username,avatar_url');

        $formattedComment = [
            'id' => $comment->id,
            'user_id' => $comment->user_id,
            'report_id' => $comment->report_id,
            'comment' => $comment->comment,
            'created_at' => $comment->created_at->toIso8601String(),
            'time_ago' => $comment->created_at->diffForHumans(),
            'user' => $comment->user,
        ];

        return response()->json([
            'message' => 'Komentar berhasil ditambahkan',
            'comment' => $formattedComment,
        ], 201);
    }
}

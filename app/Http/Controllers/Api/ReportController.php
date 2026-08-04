<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Store a newly created report in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|string',
            'height' => 'nullable|integer',
        ]);

        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('reports', 'public');
            $photoUrl = asset('storage/'.$path);
        }

        // Determine severity based on water height in cm
        $height = intval($request->input('height'));
        $severity = 'low';
        if ($height >= 50) {
            $severity = 'critical';
        } elseif ($height >= 35) {
            $severity = 'high';
        } elseif ($height >= 20) {
            $severity = 'medium';
        }

        $report = Report::create([
            'user_id' => auth()->id(),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'address' => $request->input('address'),
            'category' => $request->input('category'),
            'description' => $request->input('description'),
            'photo_url' => $photoUrl,
            'severity' => $severity,
            'status' => 'pending',
            'reported_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan banjir berhasil dikirim!',
            'data' => $report->load('user'),
        ], 201);
    }

    /**
     * Get authenticated user's reports.
     */
    public function myReports(Request $request)
    {
        $reports = Report::where('user_id', $request->user()->id)
            ->with(['user:id,name,username,avatar_url'])
            ->withCount(['likes', 'activeComments'])
            ->latest()
            ->get()
            ->map(function ($report) {
                $data = $report->toArray();
                $data['comments_count'] = $report->active_comments_count ?? 0;
                unset($data['active_comments_count']);

                return $data;
            });

        return response()->json([
            'success' => true,
            'reports' => $reports,
        ]);
    }
    /**
     * Get a single report (public — used by share links & deep links).
     */
    public function show(Request $request, $id)
    {
        $report = Report::with(['user:id,name,username,avatar_url'])
            ->withCount(['likes', 'activeComments'])
            ->findOrFail($id);

        $user = $request->user('sanctum');
        if ($user) {
            $report->loadExists(['likes as is_liked' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }]);
        }

        return response()->json([
            'success' => true,
            'report' => [
                'id' => $report->id,
                'user_id' => $report->user_id,
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
            ],
        ]);
    }

    /**
     * Update an existing report (only owner and only if still pending).
     */
    public function update(Request $request, $id)
    {
        $report = Report::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($report->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Laporan yang sudah diproses tidak dapat diedit.',
            ], 422);
        }

        $request->validate([
            'description' => 'sometimes|string',
            'category'    => 'sometimes|string',
            'height'      => 'nullable|integer',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('reports', 'public');
            $report->photo_url = asset('storage/'.$path);
        }

        if ($request->has('description')) {
            $report->description = $request->input('description');
        }

        if ($request->has('category')) {
            $report->category = $request->input('category');
        }

        if ($request->has('height')) {
            $height = intval($request->input('height'));
            $severity = 'low';
            if ($height >= 50) $severity = 'critical';
            elseif ($height >= 35) $severity = 'high';
            elseif ($height >= 20) $severity = 'medium';
            $report->severity = $severity;
        }

        $report->save();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diperbarui!',
            'data'    => $report->load('user'),
        ]);
    }
}

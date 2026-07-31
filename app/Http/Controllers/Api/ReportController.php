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
}

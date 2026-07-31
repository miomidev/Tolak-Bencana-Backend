<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = Report::query()
            ->with('user:id,name,username,avatar_url')
            ->withCount(['likes', 'comments'])
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('severity'), function ($q) use ($request) {
                $q->where('severity', $request->severity);
            })
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->where('category', $request->category);
            })
            ->when($request->filled('from'), function ($q) use ($request) {
                $q->whereDate('reported_at', '>=', $request->from);
            })
            ->when($request->filled('to'), function ($q) use ($request) {
                $q->whereDate('reported_at', '<=', $request->to);
            })
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('address', 'like', "%{$request->q}%")
                        ->orWhere('description', 'like', "%{$request->q}%");
                });
            })
            ->orderBy('reported_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $categories = Report::distinct()->pluck('category')->filter()->values();

        return view('admin.reports.index', compact('reports', 'categories'));
    }

    public function show(Report $report)
    {
        $report->load(['user:id,name,username,avatar_url,phone', 'comments.user:id,name,avatar_url'])
            ->loadCount(['likes', 'comments']);

        return view('admin.reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:verified,resolved,rejected',
            'rejection_reason' => 'nullable|string|max:500',
        ], [
            'status.required' => 'Status wajib diisi.',
            'status.in' => 'Status tidak valid.',
        ]);

        $report->status = $request->input('status');
        $report->save();

        $labels = [
            'verified' => 'Terverifikasi',
            'resolved' => 'Selesai',
            'rejected' => 'Ditolak',
        ];

        return redirect()->back()
            ->with('success', 'Laporan dari "'.($report->user?->name ?? 'Pengguna').'" ditandai: '.($labels[$report->status] ?? $report->status).'.');
    }

    public function exportExcel(Request $request)
    {
        $reports = Report::query()
            ->with('user:id,name,username')
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('from'), function ($q) use ($request) {
                $q->whereDate('reported_at', '>=', $request->from);
            })
            ->when($request->filled('to'), function ($q) use ($request) {
                $q->whereDate('reported_at', '<=', $request->to);
            })
            ->orderBy('reported_at', 'desc')
            ->get();

        $filename = 'laporan-banjir-'.now()->format('Y-m-d-His').'.xls';

        return response()->view('admin.reports.excel', compact('reports'), 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}

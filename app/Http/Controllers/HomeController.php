<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AppInfo;
use App\Models\DisasterZone;
use App\Models\Faq;
use App\Models\HelpContact;
use App\Models\Report;
use App\Models\Shelter;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HomeController extends Controller
{
    /**
     * Show the landing page with real report data for the map.
     */
    public function index()
    {
        $reports = Report::select('id', 'latitude', 'longitude', 'status', 'severity', 'address', 'description')
            ->whereIn('status', ['pending', 'verified', 'resolved'])
            ->get();

        $shelters = Shelter::all();

        $helpContacts = HelpContact::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Statistics counters
        $stats = [
            'total_reports' => Report::count(),
            'active_shelters' => Shelter::where('status', 'active')->count(),
            'resolved_cases' => Report::where('status', 'resolved')->count(),
            'monitored_points' => DisasterZone::count(),
        ];

        // Running text warning announcements (from admin CRUD)
        $announcements = Announcement::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $appInfo = AppInfo::first();

        return view('welcome', compact('reports', 'shelters', 'helpContacts', 'stats', 'announcements', 'appInfo'));
    }

    /**
     * Show the About page.
     */
    public function about()
    {
        $appInfo = AppInfo::first();

        $announcements = Announcement::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('about', compact('appInfo', 'announcements'));
    }

    /**
     * Show the FAQ page.
     */
    public function faq()
    {
        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->get();

        $announcements = Announcement::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('faq', compact('faqs', 'announcements'));
    }

    /**
     * Download the latest uploaded APK from storage.
     */
    public function downloadApk()
    {
        $appInfo = AppInfo::first();

        if (! $appInfo || ! $appInfo->apk_path) {
            abort(404, 'File APK belum tersedia.');
        }

        if (filter_var($appInfo->apk_path, FILTER_VALIDATE_URL)) {
            return redirect()->away($appInfo->apk_path);
        }

        if (! Storage::disk('public')->exists($appInfo->apk_path)) {
            abort(404, 'File APK belum tersedia.');
        }

        return Storage::disk('public')->download($appInfo->apk_path, $appInfo->apk_filename ?? 'kaliwedi.apk');
    }
}

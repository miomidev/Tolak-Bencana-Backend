<?php

namespace App\Http\Controllers;

use App\Models\AppInfo;
use App\Models\Faq;
use App\Models\Report;
use Illuminate\Http\Request;

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

        return view('welcome', compact('reports'));
    }

    /**
     * Show the About page.
     */
    public function about()
    {
        $appInfo = AppInfo::first();
        return view('about', compact('appInfo'));
    }

    /**
     * Show the FAQ page.
     */
    public function faq()
    {
        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->get();
        return view('faq', compact('faqs'));
    }
}

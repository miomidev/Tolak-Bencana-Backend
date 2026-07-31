<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlertLevel;
use App\Models\Report;
use App\Models\Shelter;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();

        $stats = [
            'reports_today' => Report::where('reported_at', '>=', $today)->count(),
            'reports_today_pct' => $this->percentChange(
                Report::where('reported_at', '>=', $today->copy()->subDay())->count(),
                Report::where('reported_at', '>=', $today)->count()
            ),
            'critical' => Report::whereIn('severity', ['high', 'critical'])->where('status', '!=', 'resolved')->count(),
            'pending' => Report::where('status', 'pending')->count(),
            'shelter_active' => Shelter::where('status', 'active')->count(),
            'shelter_total' => Shelter::count(),
            'shelter_occupancy_pct' => $this->occupancyPercent(),
            'verified' => Report::where('status', 'verified')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'total_reports' => Report::count(),
            'total_users' => User::count(),
        ];

        $latestReports = Report::with('user')
            ->orderBy('reported_at', 'desc')
            ->take(8)
            ->get();

        $shelters = Shelter::select('name', 'latitude', 'longitude', 'status', 'capacity', 'current_occupancy')->get();

        $trend = Report::selectRaw('DATE(reported_at) as date, COUNT(*) as total')
            ->where('reported_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $chartDates = [];
        $chartTotals = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i)->format('Y-m-d');
            $chartDates[] = now()->subDays($i)->format('d M');
            $chartTotals[] = $trend[$d] ?? 0;
        }

        $alertLevels = AlertLevel::where('is_active', true)->orderBy('min_height')->get();

        return view('admin.dashboard', compact(
            'stats',
            'latestReports',
            'shelters',
            'chartDates',
            'chartTotals',
            'alertLevels'
        ));
    }

    private function percentChange(int $previous, int $current): int
    {
        if ($previous <= 0) {
            return $current > 0 ? 100 : 0;
        }

        return (int) round((($current - $previous) / $previous) * 100);
    }

    private function occupancyPercent(): int
    {
        $totalCapacity = Shelter::sum('capacity');
        $totalOccupancy = Shelter::sum('current_occupancy');

        if ($totalCapacity <= 0) {
            return 0;
        }

        return (int) round(($totalOccupancy / $totalCapacity) * 100);
    }
}

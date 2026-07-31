<?php

namespace App\Providers;

use App\Models\Report;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('admin-login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip().'|'.strtolower($request->input('login', $request->input('email', ''))))
                ->response(function () {
                    return back()->withErrors([
                        'login' => 'Terlalu banyak percobaan. Silakan coba lagi dalam 1 menit.',
                    ]);
                });
        });

        View::composer('layouts.admin', function ($view) {
            $notifications = [];
            $unreadCount = 0;
            if (Schema::hasTable('reports')) {
                $notifications = Report::with('user')
                    ->where('status', 'pending')
                    ->orderBy('reported_at', 'desc')
                    ->take(5)
                    ->get();

                $unreadCount = Report::where('status', 'pending')->count();
            }
            $view->with([
                'adminNotifications' => $notifications,
                'adminUnreadCount' => $unreadCount,
            ]);
        });
    }
}

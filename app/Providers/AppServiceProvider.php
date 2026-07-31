<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Report;

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

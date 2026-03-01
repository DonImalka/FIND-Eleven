<?php

namespace App\Providers;

use App\Models\CricketMatch;
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
        // Share global live match count with the website layout
        View::composer('layouts.website', function ($view) {
            $view->with('globalLiveCount', CricketMatch::where('status', 'live')->count());
        });
    }
}

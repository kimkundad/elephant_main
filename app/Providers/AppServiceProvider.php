<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        \Illuminate\Support\Facades\View::composer(['frontend.*', 'frontend_v2.*', 'partials.admin.*', 'admin.*'], function ($view) {
            $setting = \App\Models\SiteSetting::first();
            $view->with('siteSetting', $setting);
        });

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}

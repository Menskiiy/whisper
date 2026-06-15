<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Force HTTPS when behind a proxy (Railway, Heroku, Nginx SSL termination)
        // Keeps HTTP working locally for testing
        if (
            app()->environment('production') ||
            request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
            request()->server('HTTPS') === 'on'
        ) {
            URL::forceScheme('https');
        }
    }
}

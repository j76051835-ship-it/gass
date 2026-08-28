<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('ai-chat', function (Request $request): Limit {
            return Limit::perMinute($request->user() ? 20 : 8)->by($request->user()?->getAuthIdentifier() ?: $request->session()->getId());
        });
    }
}

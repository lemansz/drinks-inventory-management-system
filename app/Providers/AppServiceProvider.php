<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\StockService;
use Illuminate\Support\Facades\View;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

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
        RateLimiter::for('login', function (Request $request) {
        // Fallback to IP if email is missing or empty
        $email = Str::lower($request->input('email', '')); 
        $key = $email ? $email . '|' . $request->ip() : $request->ip();

        return Limit::perMinute(5)->by($key);
        });

        // New Forgot Password Rate Limiter (Stricter: e.g., 3 requests per minute)
        RateLimiter::for('password-reset', function (Request $request) {
            $email = Str::lower($request->input('email', '')); 
            $key = $email ? $email . '|' . $request->ip() : $request->ip();
            
            return Limit::perMinute(3)->by($key);
        });

        View::share('StockService', app(StockService::class));
    }
}

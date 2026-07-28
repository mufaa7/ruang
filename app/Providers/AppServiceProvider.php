<?php

namespace App\Providers;


use App\Services\AI\AIManager;
use App\Services\AI\Contracts\AiProviderInterface;
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
        // Legacy AI binding removed

        // New AI architecture binding
        $this->app->singleton(AIManager::class, function ($app) {
            return new AIManager($app);
        });

        // Resolve AiProviderInterface directly to the active driver from AIManager
        $this->app->bind(AiProviderInterface::class, function ($app) {
            return $app->make(AIManager::class)->driver();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Temuan #9: Rate limiter lebih ketat untuk AI generate
        RateLimiter::for('ai-generate', function (Request $request) {
            return [
                Limit::perMinute(10)->by($request->user()?->id ?: $request->ip()),
                Limit::perDay(1000)->by($request->user()?->id ?: $request->ip()),
            ];
        });
    }
}

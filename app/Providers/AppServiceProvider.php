<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\ActivityLogStatus;

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
        // Disable activity logging if required columns are missing to avoid runtime errors
        try {
            if (!Schema::hasTable('activity_log') || !Schema::hasColumn('activity_log', 'log_name')) {
                app(ActivityLogStatus::class)->disable();
            }
        } catch (\Throwable $e) {
            // In case DB not ready, fail safe by disabling logs
            app(ActivityLogStatus::class)->disable();
            Log::warning('Activity log disabled due to schema check failure: ' . $e->getMessage());
        }
    }
}

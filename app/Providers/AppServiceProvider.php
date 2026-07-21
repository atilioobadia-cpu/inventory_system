<?php

namespace App\Providers;

use App\Services\ActivityService;
use App\Services\InvoiceService;
use App\Services\NotificationService;
use App\Services\PermissionService;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PermissionService::class, function ($app) {
            return new PermissionService();
        });

        $this->app->singleton(StockService::class, function ($app) {
            return new StockService();
        });

        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationService();
        });

        $this->app->singleton(InvoiceService::class, function ($app) {
            return new InvoiceService();
        });

        $this->app->singleton(ActivityService::class, function ($app) {
            return new ActivityService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DB::statement('SET SESSION sql_mode = "NO_ENGINE_SUBSTITUTION"');

        Gate::before(function ($user, $ability) {
            if ($user->hasPermission($ability)) {
                return true;
            }
            return null;
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\EventNotifierService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(EventNotifierService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

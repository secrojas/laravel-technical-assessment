<?php

namespace App\Providers;

use App\Services\Contracts\SwapiServiceInterface;
use App\Services\SwapiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SwapiServiceInterface::class, SwapiService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

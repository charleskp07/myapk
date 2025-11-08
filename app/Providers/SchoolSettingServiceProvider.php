<?php

namespace App\Providers;

use App\Interfaces\SchoolSettingInterface;
use App\Repositories\SchoolSettingRepository;
use Illuminate\Support\ServiceProvider;

class SchoolSettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SchoolSettingInterface::class, SchoolSettingRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

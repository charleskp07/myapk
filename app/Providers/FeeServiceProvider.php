<?php

namespace App\Providers;

use App\Interfaces\FeeInterface;
use App\Repositories\FeeRepository;
use Illuminate\Support\ServiceProvider;

class FeeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(FeeInterface::class, FeeRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

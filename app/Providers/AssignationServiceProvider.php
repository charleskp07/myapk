<?php

namespace App\Providers;

use App\Interfaces\AssignationInterface;
use App\Repositories\AssignationRepository;
use Illuminate\Support\ServiceProvider;

class AssignationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AssignationInterface::class, AssignationRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

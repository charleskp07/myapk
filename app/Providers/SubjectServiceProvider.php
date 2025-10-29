<?php

namespace App\Providers;

use App\Interfaces\SubjectInterface;
use App\Repositories\SubjectRepository;
use Illuminate\Support\ServiceProvider;

class SubjectServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SubjectInterface::class, SubjectRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

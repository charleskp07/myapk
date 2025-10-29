<?php

namespace App\Providers;

use App\Interfaces\ClassroomInterface;
use App\Repositories\ClassroomRepository;
use Illuminate\Support\ServiceProvider;

class ClassroomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ClassroomInterface::class, ClassroomRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

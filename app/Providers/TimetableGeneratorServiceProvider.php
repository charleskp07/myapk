<?php

namespace App\Providers;

use App\Interfaces\TimetableGeneratorInterface;
use App\Repositories\TimetableGeneratorRepository;
use App\Services\TimetableGeneratorService;
use Illuminate\Support\ServiceProvider;

class TimetableGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Enregistrer le service
        $this->app->singleton(TimetableGeneratorService::class, function ($app) {
            return new TimetableGeneratorService();
        });

        // Enregistrer le repository avec le service
        $this->app->bind(TimetableGeneratorRepository::class, function ($app) {
            return new TimetableGeneratorRepository(
                $app->make(TimetableGeneratorService::class)
            );
        });

        // Interface -> Repository
        $this->app->bind(TimetableGeneratorInterface::class, TimetableGeneratorRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

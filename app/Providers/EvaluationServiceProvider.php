<?php

namespace App\Providers;

use App\Interfaces\EvaluationInterface;
use App\Repositories\EvaluationRepository;
use Illuminate\Support\ServiceProvider;

class EvaluationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
       $this->app->bind(EvaluationInterface::class, EvaluationRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

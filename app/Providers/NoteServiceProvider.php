<?php

namespace App\Providers;

use App\Interfaces\NoteInterface;
use App\Repositories\NoteRepository;
use Illuminate\Support\ServiceProvider;

class NoteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(NoteInterface::class, NoteRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

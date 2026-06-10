<?php

namespace App\Providers;

use App\Http\ViewComposer\MenuComposer;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer('*', MenuComposer::class);
    }
}

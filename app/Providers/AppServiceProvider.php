<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    // Add your migrations directory here.
    private const MIGRATIONS_DIR = [
        __DIR__ . "/../Features/Masters/Migrations",
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(self::MIGRATIONS_DIR);
    }
}

<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    // protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));

        Route::middleware(['web', 'auth'])
            ->namespace($this->namespace)
            ->prefix('masters')
            ->group(base_path('app/Features/Masters/Routes/web.php'));

        Route::middleware(['web', 'auth'])
            ->namespace($this->namespace)
            ->prefix('process')
            ->group(base_path('app/Features/Process/Routes/web.php'));

        Route::middleware(['web', 'auth'])
            ->namespace($this->namespace)
            ->prefix('reports')
            ->group(base_path('app/Features/Reports/Routes/web.php'));

        Route::middleware(['web', 'auth'])
            ->namespace($this->namespace)
            ->prefix('orders-management')
            ->group(base_path('app/Features/OrderManagement/Routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));

        Route::middleware('api')
            ->namespace($this->namespace)
            ->prefix('api/masters')
            ->group(base_path('app/Features/Masters/Routes/api.php'));

        Route::middleware('api')
            ->namespace($this->namespace)
            ->prefix('api/process')
            ->group(base_path('app/Features/Process/Routes/api.php'));

        Route::middleware('api')
            ->namespace($this->namespace)
            ->prefix('api/orders')
            ->group(base_path('app/Features/OrderManagement/Routes/api.php'));
    }
}

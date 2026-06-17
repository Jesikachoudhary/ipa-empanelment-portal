<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register admin.super middleware alias so routes can use it
        if ($this->app->bound('router')) {
            $router = $this->app['router'];
            $router->aliasMiddleware('admin.super', \App\Http\Middleware\AdminSuper::class);
        }
    }
}

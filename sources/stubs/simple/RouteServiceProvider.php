<?php

namespace {$namespace}\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);
    }

    /**
     * Define the routes for the addon.
     *
     * @param \Illuminate\Routing\Router $router (injection)
     * @return void
     */
    public function map(Router $router)
    {
        $prefix = addon()->config('addon.http.prefix');
        $namespace = addon()->config('addon.namespace').'\Http\Controllers';

        $router->group(['prefix' => $prefix, 'namespace' => $namespace], function ($router) {
            require __DIR__.'/../Http/routes.php';
        });
    }
}

<?php

namespace {$namespace}\Providers;

use Illuminate\Routing\Router;
use Jumilla\Addomnipot\Laravel\Support\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);
    }

    /**
     * Define the routes for the addon.
     *
     * @param  \Illuminate\Routing\Router  $router  (injection)
     * @return void
     */
    public function map(Router $router)
    {
        parent::map($router);
    }

    /**
     * Get addon.
     *
     * @return \Jumilla\Addomnipot\Laravel\Addon
     */
    protected function addon()
    {
        return addon();
    }
}

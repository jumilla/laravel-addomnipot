<?php

namespace Jumilla\Addomnipot\Laravel\Support;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

abstract class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        $this->app->call([$this, 'map']);
    }

    /**
     * Define the routes for the addon.
     *
     * @param  \Illuminate\Routing\Router  $router  (injection)
     * @return void
     */
    public function map(Router $router)
    {
        $addon = $this->addon();
        $config = $addon->config('addon.routes');

        $attributes = [
            'domain' => array_get($config, 'domain', null),
            'prefix' => array_get($config, 'prefix', ''),
            'middleware' => array_get($config, 'middleware', []),
            'namespace' => $addon->phpNamespace().'\Http\Controllers',
        ];

        $files = array_map(function ($file) use ($addon) {
            return $addon->path($file);
        }, array_get($config, 'files', ['classes/Http/routes.php']));

        $router->group($attributes, function ($router) use ($files) {
            foreach ($files as $file) {
                require $file;
            }
        });
    }

    /**
     * Get addon.
     *
     * @return \Jumilla\Addomnipot\Laravel\Addon
     */
    abstract protected function addon();

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Pass dynamic methods onto the router instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->app->make(Router::class), $method], $parameters);
    }
}

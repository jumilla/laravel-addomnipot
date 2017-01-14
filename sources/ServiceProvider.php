<?php

namespace Jumilla\Addomnipot\Laravel;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Contracts\Http\Kernel as HttpKernel;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Addon environment.
     *
     * @var \Jumilla\Addomnipot\Laravel\Environment
     */
    protected $environment;

    /**
     * Register the service provider.
     */
    public function register()
    {
        $app = $this->app;

        $app->instance('addon', $this->environment = new Environment($app));
        $app->alias('addon', Environment::class);

        $app->singleton(Generator::class, function ($app) {
            return new Generator();
        });

        $this->registerCommands();

        $app['events']->fire(new Events\AddonWorldCreated($this->environment));

        $this->registerClassResolvers();

        $this->registerAddons();

        $app['events']->fire(new Events\AddonRegistered($this->environment));
    }

    /**
     * Register the cache related console commands.
     */
    public function registerCommands()
    {
        $this->app->singleton('command.addon.list', function ($app) {
            return new Console\AddonListCommand();
        });

        $this->app->singleton('command.addon.status', function ($app) {
            return new Console\AddonStatusCommand();
        });

        $this->app->singleton('command.addon.make', function ($app) {
            return new Console\AddonMakeCommand();
        });

        $this->app->singleton('command.addon.name', function ($app) {
            return new Console\AddonNameCommand();
        });

        $this->app->singleton('command.addon.remove', function ($app) {
            return new Console\AddonRemoveCommand();
        });

        $this->commands([
            'command.addon.list',
            'command.addon.status',
            'command.addon.make',
            'command.addon.name',
            'command.addon.remove',
        ]);
    }

    /**
     */
    protected function registerClassResolvers()
    {
        $addons = $this->environment->addons();

        ClassLoader::register($this->environment, $addons);

        AliasResolver::register($this->app['path'], $addons, $this->app['config']->get('app.aliases'));
    }

    public function registerAddons()
    {
        foreach ($this->environment->addons() as $addon) {
            $addon->register($this->app);
        }

        $this->commands($this->environment->addonConsoleCommands());

        foreach ($this->environment->addonHttpMiddlewares() as $middleware) {
            $this->app[HttpKernel::class]->pushMiddleware($middleware);
        }

        foreach ($this->environment->addonRouteMiddlewares() as $key => $middleware) {
            $this->app['router']->middleware($key, $middleware);
        }
    }

    public function boot()
    {
        foreach ($this->environment->addons() as $addon) {
            $addon->boot($this->app);
        }

        $this->app['events']->fire(new Events\AddonBooted($this->environment));
    }
}

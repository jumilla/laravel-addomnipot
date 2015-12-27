<?php

namespace Jumilla\Addomnipot\Laravel;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Addon environment.
     *
     * @var \Jumilla\Addomnipot\Laravel\Environment
     */
    protected $addonEnvironment;

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->instance('addon', $this->addonEnvironment = new AddonEnvironment($app));
        $this->app->alias('addon', AddonEnvironment::class);

        $this->app->singleton(Generator::class, function ($app) {
            return new Generator();
        });

        $this->registerCommands();

        $this->registerClassResolvers();
    }

    /**
     * Register the cache related console commands.
     */
    public function registerCommands()
    {
        $this->app->singleton('command.addon.check', function ($app) {
            return new Console\AddonCheckCommand();
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

        $this->app->singleton('command.addon.status', function ($app) {
            return new Console\AddonStatusCommand();
        });

        $this->commands([
            'command.addon.check',
            'command.addon.make',
            'command.addon.name',
            'command.addon.remove',
            'command.addon.status',
        ]);
    }

    /**
     */
    protected function registerClassResolvers()
    {
        AddonClassLoader::register($this->addonEnvironment, $this->addonEnvironment->addons());

        AliasResolver::register($this->app['path'], $addons, $this->app['config']->get('app.aliases'));
    }
}

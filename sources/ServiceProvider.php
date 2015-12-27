<?php

namespace Jumilla\Addomnipot\Laravel;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton(Environment::class, function ($app) {
            return new Environment();
        });

        $this->app->singleton(Directory::class, function ($app) {
            return new Directory($app);
        });

        $this->app->singleton(Generator::class, function ($app) {
            return new Generator();
        });

        $this->registerCommands();
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
}

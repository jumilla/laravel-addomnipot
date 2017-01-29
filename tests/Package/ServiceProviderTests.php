<?php

use Jumilla\Addomnipot\Laravel\ServiceProvider;
use Jumilla\Addomnipot\Laravel\Commands;
use Jumilla\Addomnipot\Laravel\Events;

class ServiceProviderTests extends TestCase
{
    public function test_addonWorldEvent()
    {
        $app = $this->createApplication();

        $app['config']->set('app.aliases', []);

        $created = 0;
        $registered = 0;
        $booted = 0;
        $app['events']->listen(Events\AddonWorldCreated::class, function ($env) use (&$created) {
        	++$created;
        });
        $app['events']->listen(Events\AddonRegistered::class, function ($env) use (&$registered) {
        	++$registered;
        });
        $app['events']->listen(Events\AddonBooted::class, function ($env) use (&$booted) {
        	++$booted;
        });

        $provider = new ServiceProvider($app);
        $provider->register();
        $provider->boot();

        Assert::same(1, $created);
        Assert::same(1, $registered);
        Assert::same(1, $booted);
    }

    use MockeryTrait;

    /**
     * @test
     */
    public function test_register()
    {
        // 1. setup
        $app = $this->createApplication();
        $provider = new ServiceProvider($app);

        // 2. test
        $provider->register();

        Assert::isInstanceOf(Commands\AddonListCommand::class, $app['command.addon.list']);
        Assert::isInstanceOf(Commands\AddonMakeCommand::class, $app['command.addon.make']);
        Assert::isInstanceOf(Commands\AddonNameCommand::class, $app['command.addon.name']);
        Assert::isInstanceOf(Commands\AddonRemoveCommand::class, $app['command.addon.remove']);
        Assert::isInstanceOf(Commands\AddonStatusCommand::class, $app['command.addon.status']);
    }
}

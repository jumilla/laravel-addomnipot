<?php

use Jumilla\Addomnipot\Laravel\ServiceProvider;
use Jumilla\Addomnipot\Laravel\Events;

class ServiceProviderTests extends TestCase
{
    public function test_withNoParameter()
    {
        $app = $this->createApplication();

        $app['config']->set('app.aliases', []);

        $created = 0;
        $registered = 0;
        $booted = 0;
        $app['events']->listen(Events\AddonWorldCreated::class, function ($env) use (&$created) {
        	++$created;
        });
        $app['events']->listen(Events\AddonWorldCreated::class, function ($env) use (&$registered) {
        	++$registered;
        });
        $app['events']->listen(Events\AddonWorldCreated::class, function ($env) use (&$booted) {
        	++$booted;
        });

        $provider = new ServiceProvider($app);
        $provider->register();
        $provider->boot();

        Assert::same(1, $created);
        Assert::same(1, $registered);
        Assert::same(1, $booted);
    }
}

<?php

use Jumilla\Addomnipot\Laravel\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;

class ServiceProviderTests extends TestCase
{
    public function test_withNoParameter()
    {
        $app = $this->createApplication();

        $app['config']->set('app.aliases', []);

        $command = new ServiceProvider($app);

        $command->register();
        $command->boot();
    }
}

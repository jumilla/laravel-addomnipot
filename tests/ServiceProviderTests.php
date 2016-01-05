<?php

use Jumilla\Addomnipot\Laravel\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;

class ServiceProviderTests extends TestCase
{
    public function test_withNoParameter()
    {
        $app = $this->createApplication();

        $app['events'] = $this->createMock(Dispatcher::class);
        $app['events']->shouldReceive('listen')->once();

        $app['config']->set('app.aliases', []);

        $command = new ServiceProvider($app);
        $app['events']->shouldReceive('listen')->once();

        $command->register();
        $command->boot();
    }
}

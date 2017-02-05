<?php

use Jumilla\Addomnipot\Laravel\Environment as AddonEnviroment;

class AddonEnvironmentTests extends TestCase
{
    /**
     * @test
     */
    public function test_withNoParameter()
    {
        $app = $this->createApplication();
        $instance = new AddonEnviroment($app);

        Assert::isInstanceOf(AddonEnviroment::class, $instance);

        Assert::null($instance->addon('foo'));
    }

    public function test_classToPathMethod()
    {
        $app = $this->createApplication();
        $instance = new AddonEnviroment($app);

        Assert::same('DatabaseServiceProvider.php', $instance->classToPath('DatabaseServiceProvider'));
        Assert::same('Providers/DatabaseServiceProvider.php', $instance->classToPath('Providers\DatabaseServiceProvider'));
    }

    public function test_pathToClassMethod()
    {
        $app = $this->createApplication();
        $instance = new AddonEnviroment($app);

        Assert::same('DatabaseServiceProvider', $instance->pathToClass('DatabaseServiceProvider.php'));
        Assert::same('Providers\DatabaseServiceProvider', $instance->pathToClass('Providers/DatabaseServiceProvider.php'));
    }

    public function test_addonsMethod()
    {
        $app = $this->createApplication();
        $instance = new AddonEnviroment($app);

        $app['config']->set('addon.path', 'tmp');

        Assert::same([], $instance->addons());
    }
}

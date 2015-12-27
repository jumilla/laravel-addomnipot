<?php

use Illuminate\Container\Container;
use Illuminate\Config\Repository as Config;
use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Jumilla\Addomnipot\Laravel\Environment as AddonEnvironment;
use Jumilla\Addomnipot\Laravel\Generator as AddonGenerator;
use Jumilla\Versionia\Laravel\Migrator;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    use MockeryTrait;

    /**
     * @before
     */
    public function setupSandbox()
    {
        $files = new Filesystem();
        $files->deleteDirectory(__DIR__.'/sandbox');
        $files->makeDirectory(__DIR__.'/sandbox');
        $files->makeDirectory(__DIR__.'/sandbox/addons');
        $files->makeDirectory(__DIR__.'/sandbox/app');
        $files->makeDirectory(__DIR__.'/sandbox/config');
    }

    /**
     * @after
     */
    public function teardownSandbox()
    {
        $files = new Filesystem();
        $files->deleteDirectory(__DIR__.'/sandbox');
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    protected function createApplication()
    {
        Container::setInstance($this->app = new ApplicationStub([
        ]));

        $this->app['config'] = new Config([]);
        $this->app['files'] = new Filesystem();
        $this->app['filesystem'] = new FilesystemManager($this->app);
        $this->app[AddonEnvironment::class] = new AddonEnvironment($this->app);
        $this->app[AddonGenerator::class] = new AddonGenerator();

        return $this->app;
    }

    protected function createAddon($name, $type, array $arguments)
    {
        (new AddonGenerator())->generateAddon($this->app->basePath().'/addons/'.$name, $type, $arguments);
    }
}

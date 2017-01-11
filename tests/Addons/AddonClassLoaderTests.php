<?php

use Jumilla\Addomnipot\Laravel\Environment as AddonEnvironment;
use Jumilla\Addomnipot\Laravel\ClassLoader as AddonClassLoader;
use Jumilla\Addomnipot\Laravel\Addon;
use Illuminate\Config\Repository;

class AddonClassLoaderTests extends TestCase
{
    public function test_registerAndUnregisterMethod()
    {
        $app = $this->createApplication();
        AddonClassLoader::register($app[AddonEnvironment::class], []);

        AddonClassLoader::unregister();
    }

    public function test_loadMethod()
    {
        $addon = new Addon('foo', __DIR__.'/../sandbox/addons/foo', [
            'namespace' => 'Foo',
            'directories' => [
                'classes',
            ],
        ]);

        $app = $this->createApplication();
        $loader = new AddonClassLoader($app[AddonEnvironment::class], [$addon]);

        Assert::false($loader->load('Foo\\Bar'));
        Assert::false($loader->load('Bar\\Baz'));
    }
}

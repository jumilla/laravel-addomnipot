<?php

use Jumilla\Addomnipot\Laravel\ClassLoader as AddonClassLoader;
use Jumilla\Addomnipot\Laravel\Addon;
use Illuminate\Config\Repository;

class AddonClassLoaderTests extends TestCase
{
    public function test_registerAndUnregisterMethod()
    {
        AddonClassLoader::register([]);
        AddonClassLoader::unregister();
    }

    public function test_loadMethod()
    {
        $addon = new Addon('foo', __DIR__.'/../sandbox/addons/foo', new Repository([
            'addon' => [
                'namespace' => 'Foo',
                'directories' => [
                    'classes',
                ],
            ],
        ]));

        $loader = new AddonClassLoader([$addon]);

        Assert::false($loader->load('Foo\\Bar'));
        Assert::false($loader->load('Bar\\Baz'));

        AddonClassLoader::unregister();
    }
}

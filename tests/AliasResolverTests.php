<?php

use Jumilla\Addomnipot\Laravel\AliasResolver;
use Jumilla\Addomnipot\Laravel\Addon;
use Illuminate\Config\Repository;

class AliasResolverTests extends TestCase
{
    public function test_registerAndUnregisterMethod()
    {
        AliasResolver::register(__DIR__, [], []);
        AliasResolver::unregister();
    }

    public function test_loadMethod()
    {
        $addon1 = new Addon('foo', __DIR__.'/../addons/foo', [
            'namespace' => 'Foo',
            'aliases' => [
                'Config' => 'Illuminate\Config\Repository',
            ],
            'includes_global_aliases' => true,
        ]);
        $addon2 = new Addon('bar', __DIR__.'/../addons/bar', [
            'namespace' => '',
        ]);

        $resolver = new AliasResolver(__DIR__.'/../app', [$addon1, $addon2], [
            'File' => 'Illuminate\Filesystem\Filesystem',
        ]);

        Assert::true($resolver->load('Foo\File'));
        Assert::true($resolver->load('Foo\Config'));
        Assert::false($resolver->load('Nothing'));

        AliasResolver::unregister();
    }
}

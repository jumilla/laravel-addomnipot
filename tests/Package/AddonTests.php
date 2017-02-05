<?php

use Jumilla\Addomnipot\Laravel\Addon;
use Jumilla\Addomnipot\Laravel\Registrar;
use Illuminate\Config\Repository;
use Illuminate\Translation\Translator;

class AddonTests extends TestCase
{
    public function test_createNoExistingAddon()
    {
        try {
            Addon::create(null, 'foo', 'bar');

            Assert::failure();
        }
        catch (RuntimeException $ex) {
            Assert::success();
        }
    }

    public function test_createExistingAddon()
    {
        $app = $this->createApplication();
        $app['translator'] = $this->createMock(Translator::class);
        $app['view'] = $this->createMock(Translator::class);
        $app['specs'] = $this->createMock(Translator::class);

        $app['translator']->shouldReceive('addNamespace')->once();
        $app['view']->shouldReceive('addNamespace')->once();
        $app['specs']->shouldReceive('addNamespace')->once();

        $addon = $this->getAddon('foo');
        (new Registrar)->register($app, [$addon]);
        (new Registrar)->boot($app, [$addon]);

        Assert::success();
    }

    public function test_attributeAccessMethods()
    {
        $app = $this->createApplication();
        $addon = new Addon($app, 'foo', $app->basePath().'/addons/foo', [
            'namespace' => 'Foo\\',
        ]);
        Assert::same('foo', $addon->name());
        Assert::same($app->basePath().'/addons/foo', $addon->path());
        Assert::same($app->basePath().'/addons/foo/bar', $addon->path('bar'));
        Assert::same('addons/foo', $addon->relativePath($app));
        Assert::same(5, $addon->version());
        Assert::same('Foo', $addon->phpNamespace());
    }

    public function test_resourceAccessMethods()
    {
        $app = $this->createApplication();
        $app['translator'] = $this->createMock(Translator::class);
        $addon = new Addon($app, 'foo', $app->basePath().'/addons/foo', []);

        $app['translator']->shouldReceive('trans')->with('foo::bar')->andReturn('baz')->once();
        $app['translator']->shouldReceive('transChoice')->with('foo::bar', 1)->andReturn('baz')->once();

        Assert::same('baz', $addon->config('bar', 'baz'));
        Assert::same('baz', $addon->trans('bar'));
        Assert::same('baz', $addon->transChoice('bar', 1));
    }

    public function test_registerV5Addon()
    {
        $app = $this->createApplication();
        $addon = new Addon($app, 'foo', $app->basePath().'/addons/foo', [
            'version' => 5,
            'namespace' => 'Foo',
        ]);

        (new Registrar)->register($app, [$addon]);

        Assert::same('foo', $addon->name());
        Assert::same($app->basePath().'/addons/foo', $addon->path());
        Assert::same(5, $addon->version());
        Assert::same('Foo', $addon->phpNamespace());
    }

    public function test_bootV5Addon()
    {
        $app = $this->createApplication();
        $addon = new Addon($app, 'foo', $app->basePath().'/addons/foo', [
            'version' => 5,
            'namespace' => 'Foo',
        ]);

        (new Registrar)->boot($app, [$addon]);
    }

    protected function getAddon($name)
    {
        $this->createAddon($name, 'ui', [
            'addon_name' => $name,
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        $path = $this->app->basePath().'/addons/'.$name;

        return Addon::create($this->app, $name, $path);
    }
}

<?php

use Jumilla\Addomnipot\Laravel\Addon;
use Illuminate\Config\Repository;
use Illuminate\Translation\Translator;

class AddonTests extends TestCase
{
    public function test_createNoExistingAddon()
    {
        try {
            Addon::create('foo');

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
        $addon->register($app);
        $addon->boot($app);

        Assert::success();
    }

    public function test_attributeAccessMethods()
    {
        $app = $this->createApplication();
        $addon = new Addon('foo', $app->basePath().'/addons/foo', new Repository([
            'addon' => [
                'namespace' => 'Foo\\',
            ],
        ]));
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
        $addon = new Addon('foo', $app->basePath().'/addons/foo', new Repository);

        $app['translator']->shouldReceive('trans')->with('foo::bar')->andReturn('baz')->once();
        $app['translator']->shouldReceive('transChoice')->with('foo::bar', 1)->andReturn('baz')->once();

        $addon->register($app);
        Assert::same('baz', $addon->config('bar', 'baz'));
        Assert::same('baz', $addon->trans('bar'));
        Assert::same('baz', $addon->transChoice('bar', 1));
    }

    public function test_registerV5Addon()
    {
        $app = $this->createApplication();
        $addon = new Addon('foo', $app->basePath().'/addons/foo', new Repository([
            'addon' => [
                'version' => 5,
                'namespace' => 'Foo',
            ],
        ]));

        $addon->register($app);

        Assert::same('foo', $addon->name());
        Assert::same($app->basePath().'/addons/foo', $addon->path());
        Assert::same(5, $addon->version());
        Assert::same('Foo', $addon->phpNamespace());
    }

    public function test_bootV5Addon()
    {
        $app = $this->createApplication();
        $addon = new Addon('foo', $app->basePath().'/addons/foo', new Repository([
            'addon' => [
                'version' => 5,
                'namespace' => 'Foo',
            ],
        ]));

        $addon->boot($app);
    }

    public function test_registerV4Addon()
    {
        $app = $this->createApplication();
        $addon = new Addon('foo', $app->basePath().'/addons/foo', new Repository([
            'addon' => [
                'version' => 4,
                'namespace' => 'Foo',
            ],
        ]));

        $addon->register($app);

        Assert::same('foo', $addon->name());
        Assert::same($app->basePath().'/addons/foo', $addon->path());
        Assert::same(4, $addon->version());
        Assert::same('Foo', $addon->phpNamespace());
    }

    public function test_bootV4Addon()
    {
        $app = $this->createApplication();
        $addon = new Addon('foo', $app->basePath().'/addons/foo', new Repository([
            'addon' => [
                'version' => 4,
                'namespace' => 'Foo',
            ],
        ]));

        $addon->boot($app);
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

        return Addon::create($path);
    }
}

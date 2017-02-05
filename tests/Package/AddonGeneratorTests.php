<?php

use Jumilla\Addomnipot\Laravel\Generator as AddonGenerator;

class AddonGeneratorTests extends TestCase
{
    public function test_withNoParameter()
    {
        $command = new AddonGenerator();

        Assert::isInstanceOf(AddonGenerator::class, $command);
    }

    public function test_makeMinimum()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'minimum', [
            'addon_name' => 'foo',
            'namespace' => 'Foo',
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeSimple()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'simple', [
            'addon_name' => 'foo',
            'namespace' => 'Foo',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeLibrary()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'library', [
            'addon_name' => 'foo',
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeApi()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'api', [
            'addon_name' => 'foo',
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeUi()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'ui', [
            'addon_name' => 'foo',
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeUISample()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'ui-sample', [
            'addon_name' => 'foo',
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeDebug()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'debug', [
            'addon_name' => 'foo',
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeGenerator()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'generator', [
            'addon_name' => 'foo',
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeLaravel5()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'laravel5', [
            'addon_name' => 'foo',
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeLaravel5Auth()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'laravel5-auth', [
            'addon_name' => 'foo',
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }
}

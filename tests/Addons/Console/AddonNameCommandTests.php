<?php

use Jumilla\Addomnipot\Laravel\Console\AddonNameCommand as Command;

class AddonNameCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withoutArguments()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = new Command();

        try {
            $result = $this->runCommand($app, $command);

            Assert::failure();
        } catch (RuntimeException $ex) {
            Assert::stringStartsWith('Not enough arguments', $ex->getMessage());
        }
    }

    public function test_withAddonParameter()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = new Command();

        try {
            $result = $this->runCommand($app, $command, [
                'addon' => 'foo',
            ]);

            Assert::failure();
        } catch (RuntimeException $ex) {
            Assert::stringStartsWith('Not enough arguments', $ex->getMessage());
        }
    }

    public function test_withAddonAndNamespaceParameter_addonNotFound()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = new Command();

        try {
            $result = $this->runCommand($app, $command, [
                'addon' => 'foo',
                'namespace' => 'bar',
            ]);

            Assert::failure();
        } catch (RuntimeException $ex) {
            Assert::equals("Addon 'foo' is not found.", $ex->getMessage());
        }
    }

    public function test_withAddonAndNamespaceParameter_addonFound()
    {
        // 1. setup
        $app = $this->createApplication();
        $this->createAddon('foo', 'ui', [
            'addon_name' => 'foo',
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        // 2. condition

        // 3. test
        $command = new Command();

        $result = $this->runCommand($app, $command, [
            'addon' => 'foo',
            'namespace' => 'bar',
            '--force' => true,
        ]);

        Assert::same(0, $result);
    }

    public function test_userCancel()
    {
        // 1. setup
        $app = $this->createApplication();
        $this->createAddon('foo', 'minimum', [
            'addon_name' => 'foo',
            'namespace' => 'foo',
        ]);

        // 2. condition
        $command = Mockery::mock(Command::class.'[confirm]');
        $command->shouldReceive('confirm')->once()->andReturn(false);

        // 3. test
        $this->runCommand($app, $command, [
            'addon' => 'foo',
            'namespace' => 'bar',
        ]);
    }
}

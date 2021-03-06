<?php

use Jumilla\Addomnipot\Laravel\Commands\AddonRemoveCommand as Command;

class AddonRemoveCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withoutArguments()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        try {
            $this->runCommand($app, $command);

            Assert::failure();
        } catch (RuntimeException $ex) {
            Assert::stringStartsWith('Not enough arguments', $ex->getMessage());
        }
    }

    public function test_withName_addonNotFound()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        try {
            $this->runCommand($app, $command, [
                'addon' => 'foo',
            ]);

            Assert::failure();
        } catch (UnexpectedValueException $ex) {
            Assert::equals('Addon "foo" is not found.', $ex->getMessage());
        }
    }

    public function test_withName_addonFound()
    {
        // 1. setup
        $app = $this->createApplication();
        $this->createAddon('foo', 'minimum', [
            'addon_name' => 'foo',
            'namespace' => 'foo',
        ]);

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        $this->runCommand($app, $command, [
            'addon' => 'foo',
            '--force' => true,
        ]);
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
        ]);
    }
}

<?php

use Jumilla\Addomnipot\Laravel\Commands\AddonMakeCommand as Command;

class AddonMakeCommandTests extends TestCase
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

    public function test_withName_andType()
    {
        $app = $this->createApplication();

        $this->runMakeCommand($app, 'minimum');
        $this->runMakeCommand($app, 'simple');
        $this->runMakeCommand($app, 'asset');
        $this->runMakeCommand($app, 'library');
        $this->runMakeCommand($app, 'api');
        $this->runMakeCommand($app, 'ui');
        $this->runMakeCommand($app, 'ui-sample');
        $this->runMakeCommand($app, 'generator');
        $this->runMakeCommand($app, 'debug');
        $this->runMakeCommand($app, 'laravel5');
        $this->runMakeCommand($app, 'laravel5-auth');
    }

    public function test_withNoNamespace()
    {
        $app = $this->createApplication();
        $command = $app->make(Command::class);

        return $this->runCommand($app, $command, [
            'name' => 'foo',
            'skeleton' => 'minimum',
            '--no-namespace' => true,
            '--yes' => true,
        ]);
    }

    public function test_withNamespace()
    {
        $app = $this->createApplication();
        $command = $app->make(Command::class);

        return $this->runCommand($app, $command, [
            'name' => 'foo',
            'skeleton' => 'minimum',
            '--namespace' => 'Bar',
            '--yes' => true,
        ]);
    }

    public function runMakeCommand($app, $skeleton)
    {
        $command = $app->make(Command::class);

        return $this->runCommand($app, $command, [
            'name' => $skeleton,
            'skeleton' => $skeleton,
            '--yes' => true,
        ]);
    }
}

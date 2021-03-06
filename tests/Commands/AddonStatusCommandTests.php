<?php

use Jumilla\Addomnipot\Laravel\Commands\AddonStatusCommand as Command;

class AddonStatusCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    /**
     * @test
     */
    public function test_withNoParameter()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition
        $this->createAddon('foo', 'minimum', [
            'addon_name' => 'foo',
            'namespace' => 'Foo',
        ]);

        // 3. test
        $command = new Command();

        $result = $this->runCommand($app, $command);

        Assert::same(0, $result);
    }

    /**
     * @test
     */
    public function test_withAddonParameter()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition
        $this->createAddon('foo', 'minimum', [
            'addon_name' => 'foo',
            'namespace' => 'Foo',
        ]);

        // 3. test
        $command = new Command();

        $result = $this->runCommand($app, $command, [
            'addon' => 'foo',
        ]);

        Assert::same(0, $result);
    }
}

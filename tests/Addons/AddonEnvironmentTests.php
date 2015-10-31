<?php

use Jumilla\Addomnipot\Laravel\Environment;

class AddonEnvironmentTests extends TestCase
{
    /**
     * @test
     */
    public function test_withNoParameter()
    {
        $environment = new Environment();

        Assert::isInstanceOf(Environment::class, $environment);

        Assert::null($environment->getAddon('foo'));
        Assert::isEmpty($environment->getAddonConsoleCommands('foo'));
    }
}

<?php

use Jumilla\Addomnipot\Laravel\Repository\ConfigLoader;

class ConfigLoaderTests extends TestCase
{
    public function test_withNoParameter()
    {
        $loader = new ConfigLoader();

//        $loader->load(__DIR__);

        Assert::isInstanceOf(ConfigLoader::class, $loader);
    }
}

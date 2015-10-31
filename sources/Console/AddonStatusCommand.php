<?php

namespace Jumilla\Addomnipot\Laravel\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Jumilla\Addomnipot\Laravel\Directory as AddonDirectory;

class AddonStatusCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'addon:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List up addon information';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Filesystem $filesystem)
    {
        // make addons/
        $addonsDirectory = AddonDirectory::path();
        if (!$filesystem->exists($addonsDirectory)) {
            $filesystem->makeDirectory($addonsDirectory);
        }

        // copy app/config/addon.php
        $addonConfigSourceFile = __DIR__.'/../../../config/addon.php';
        $addonConfigFile = app('path.config').'/addon.php';
        if (!$filesystem->exists($addonConfigFile)) {
            $filesystem->copy($addonConfigSourceFile, $addonConfigFile);

            $this->info('make config: '.$addonConfigFile);
        }

        // show lists
        $addons = AddonDirectory::addons();
        foreach ($addons as $addon) {
            $this->dump($addon);
        }
    }

    protected function dump($addon)
    {
        $this->line($addon->name());
    }
}

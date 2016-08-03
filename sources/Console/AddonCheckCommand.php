<?php

namespace Jumilla\Addomnipot\Laravel\Console;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;
use Jumilla\Addomnipot\Laravel\Environment as AddonEnvironment;

class AddonCheckCommand extends Command
{
    use Functions;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'addon:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check addons';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = $this->laravel['files'];
        $env = $this->laravel[AddonEnvironment::class];

        // make addons/
        $addonsDirectory = $env->path();
        if (!$files->exists($addonsDirectory)) {
            $files->makeDirectory($addonsDirectory);
        }

        $this->line('> Check Start.');
        $this->line('--------');

        $addons = $env->addons();
        foreach ($addons as $addon) {
            $this->dump($addon);
        }

        $this->line('> Check Finished!');
    }

    protected function dump($addon)
    {
        $this->dumpProperties($addon);
        $this->dumpClasses($addon);
        $this->dumpServiceProviders($addon);

        $this->line('--------');
    }

    protected function dumpProperties($addon)
    {
        $this->info(sprintf('Addon "%s"', $addon->name()));
        $this->info(sprintf('Path: %s', $addon->relativePath($this->laravel)));
        $this->info(sprintf('PHP namespace: %s', $addon->phpNamespace()));
    }

    protected function dumpClasses($addon)
    {
        // load laravel services
        $files = $this->laravel['files'];
        $env = $this->laravel[AddonEnvironment::class];

        // 全ディレクトリ下を探索する (PSR-4)
        foreach ($addon->config('addon.directories') as $directory) {
            $this->info(sprintf('PHP classes on "%s"', $directory));

            $classDirectoryPath = $addon->path($directory);

            if (!file_exists($classDirectoryPath)) {
                $this->line(sprintf('Warning: Class directory "%s" not found', $directory));
                continue;
            }

            // recursive find files
            $phpFilePaths = iterator_to_array((new Finder())->in($classDirectoryPath)->name('*.php')->files(), false);

            foreach ($phpFilePaths as $phpFilePath) {
                $relativePath = substr($phpFilePath, strlen($classDirectoryPath) + 1);

                $classFullName = $addon->phpNamespace().'\\'.$env->pathToClass($relativePath);

                $this->line(sprintf('  "%s" => %s', $relativePath, $classFullName));
            }
        }
    }

    protected function dumpServiceProviders($addon)
    {
    }
}

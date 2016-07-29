<?php

namespace Jumilla\Addomnipot\Laravel;

use Illuminate\Contracts\Foundation\Application;

class Environment
{
    /**
     * @return array
     */
    protected $addons = null;

    /**
     * @return array
     */
    protected $addonsPaths = [];

    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->makeAddonsPaths();
    }

    /**
     * @return void
     */
    private function makeAddonsPaths()
    {
        $addonsDirectoryPath = $this->path();

        $this->addonsPaths[''] = $addonsDirectoryPath;
        foreach ($this->app['config']->get('addon.additional_paths', []) as $name => $path) {
            $this->addonsPaths[$name] = $this->app->basePath().'/'.$path;
        }
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function path($name = null)
    {
        if ($name !== null) {
            return static::path().'/'.$name;
        } else {
            return $this->app->basePath().'/'.$this->app['config']->get('addon.path', 'addons');
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function exists($name)
    {
        return is_dir($this->path($name));
    }

    /**
     * @param string $relativeClassName
     *
     * @return string
     */
    public function classToPath($relativeClassName)
    {
        return str_replace('\\', '/', $relativeClassName).'.php';
    }

    /**
     * @param string $relativePath
     *
     * @return mixed
     */
    public function pathToClass($relativePath)
    {
        if (strpos($relativePath, '/') !== false) {
            $relativePath = dirname($relativePath).'/'.basename($relativePath, '.php');
        } else {
            $relativePath = basename($relativePath, '.php');
        }

        return str_replace('/', '\\', $relativePath);
    }

    /**
     * @return array
     */
    public function loadAddons()
    {
        $files = $this->app['files'];

        $addons = [];

        foreach ($this->addonsPaths as $path) {
            // make directory
            if (!$files->exists($path)) {
                $files->makeDirectory($path);
            }

            // load addons
            foreach ($files->directories($path) as $dir) {
                $addon = Addon::create($dir);

                $addons[$addon->name()] = $addon;
            }
        }

        return $addons;
    }

    /**
     * @return array
     */
    public function addons()
    {
        if ($this->addons === null) {
            $this->addons = $this->loadAddons();
        }

        return $this->addons;
    }

    /**
     * @return \Jumilla\Addomnipot\Laravel\Addons\Addon
     */
    public function addon($name)
    {
        return array_get($this->addons(), $name ?: '', null);
    }

    /**
     * @return array
     */
    public function addonConsoleCommands()
    {
        $commands = [];

        foreach ($this->addons() as $addon) {
            $commands = array_merge($commands, $addon->config('addon.console.commands', []));
        }

        return $commands;
    }

    /**
     * @return array
     */
    public function addonHttpMiddlewares()
    {
        $middlewares = [];

        foreach ($this->addons() as $addon) {
            $middlewares = array_merge($middlewares, $addon->config('addon.http.middlewares', []));
        }

        return $middlewares;
    }

    /**
     * @return array
     */
    public function addonRouteMiddlewares()
    {
        $middlewares = [];

        foreach ($this->addons() as $addon) {
            $middlewares = array_merge($middlewares, $addon->config('addon.http.route_middlewares', []));
        }

        return $middlewares;
    }
}

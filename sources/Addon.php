<?php

namespace Jumilla\Addomnipot\Laravel;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Config\Repository;
use Symfony\Component\Finder\Finder;
use RuntimeException;

class Addon
{
    /**
     * @param string $path
     *
     * @return static
     */
    public static function create($path)
    {
        $pathComponents = explode('/', $path);

        $name = $pathComponents[count($pathComponents) - 1];

        $config = static::loadAddonConfig($path, $name);

        return new static($name, $path, $config);
    }

    /**
     * @param string $path
     * @param string $name
     *
     * @return array
     */
    protected static function loadAddonConfig($path, $name)
    {
        if (file_exists($path.'/addon.php')) {
            $config = require $path.'/addon.php';
        } else {
            throw new RuntimeException("No such config file for addon '$name', need 'addon.php'.");
        }

        $version = array_get($config, 'version', 5);
        if ($version != 5) {
            throw new RuntimeException($version.': Illigal addon version.');
        }

        return $config;
    }

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * @param string  $name
     * @param string  $path
     * @param array   $config
     */
    public function __construct($name, $path, array $config)
    {
        $this->name = $name;
        $this->path = $path;
        $this->config = new Repository();
        $this->config->set('addon', $config);
    }

    /**
     * get name.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * get fullpath.
     *
     * @param string $path
     *
     * @return string
     */
    public function path($path = null)
    {
        if (func_num_args() == 0) {
            return $this->path;
        } else {
            return $this->path.'/'.$path;
        }
    }

    /**
     * get relative path.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return string
     */
    public function relativePath(Application $app)
    {
        return substr($this->path, strlen($app->basePath()) + 1);
    }

    /**
     * get version.
     *
     * @return int
     */
    public function version()
    {
        return $this->config('addon.version', 5);
    }

    /**
     * get PHP namespace.
     *
     * @return string
     */
    public function phpNamespace()
    {
        return trim($this->config('addon.namespace', ''), '\\');
    }

    /**
     * get config value.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return $this->config->get($key, $default);
    }

    /**
     * Get a lang resource name
     *
     * @param string $resource
     *
     * @return string
     */
    public function transName($resource)
    {
        return $this->name.'::'.$resource;
    }

    /**
     * Translate the given message.
     *
     * @param string $id
     * @param array $parameters
     * @param string $domain
     * @param string $locale
     * @return string
     */
    public function trans()
    {
        $args = func_get_args();
        $args[0] = $this->transName($args[0]);

        return call_user_func_array([$this->app['translator'], 'trans'], $args);
    }

    /**
     * Translates the given message based on a count.
     *
     * @param string $id
     * @param int $number
     * @param array $parameters
     * @param string $domain
     * @param string $locale
     * @return string
     */
    public function transChoice()
    {
         $args = func_get_args();
         $args[0] = $this->transName($args[0]);

         return call_user_func_array([$this->app['translator'], 'transChoice'], $args);
    }

    /**
     * Get a view resource name
     *
     * @param string $resource
     *
     * @return string
     */
    public function viewName($resource)
    {
        return $this->name.'::'.$resource;
    }

    /**
     * @param string $view
     * @param array $data
     * @param array $mergeData
     *
     * @return \Illuminate\View\View
     */
    public function view($view, $data = [], $mergeData = [])
    {
        return $this->app['view']->make($this->viewname($view), $data, $mergeData);
    }

    /**
     * Get a spec resource name
     *
     * @param string $resource
     *
     * @return string
     */
    public function specName($resource)
    {
        return $this->name.'::'.$resource;
    }

    /**
     * Get spec.
     *
     * @param string $path
     *
     * @return \Jumilla\Addomnipot\Laravel\Specs\InputSpec
     */
    public function spec($path)
    {
        return $this->app[SpecFactory::class]->make($this->specName($path));
    }

    /**
     * register addon.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function register(Application $app)
    {
        $this->app = $app;

        // prepare helper functions
        $this->loadFiles($this->config('addon.files', []));

        // load config
        $this->loadConfigurationFiles($this->path($this->config('addon.paths.config', 'config')));

        // regist service providers
        $providers = $this->config('addon.providers', []);
        foreach ($providers as $provider) {
            $app->register($provider);
        }
    }

    /**
     * boot addon.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function boot(Application $app)
    {
        $this->registerPackage($app);
    }

    /**
     * Load the configuration items from all of the files.
     *
     * @param string $directoryPath
     */
    protected function loadConfigurationFiles($directoryPath)
    {
        foreach ($this->getConfigurationFiles($directoryPath) as $group => $path) {
            $this->config->set($group, require $path);
        }
    }

    /**
     * Get all of the configuration files for the directory.
     *
     * @param string $directoryPath
     *
     * @return array
     */
    protected function getConfigurationFiles($directoryPath)
    {
        $files = [];

        if (is_dir($directoryPath)) {
            foreach (Finder::create()->files()->in($directoryPath) as $file) {
                $group = basename($file->getRealPath(), '.php');
                $files[$group] = $file->getRealPath();
            }
        }

        return $files;
    }

    /**
     * load addon initial script files.
     *
     * @param array $files
     */
    protected function loadFiles(array $files)
    {
        foreach ($files as $filename) {
            $path = $this->path($filename);

            if (!file_exists($path)) {
                $message = "Warning: PHP Script '$path' is nothing.";
                info($message);
                echo $message;
                continue;
            }

            require_once $path;
        }
    }
    /**
     * Register the package's component namespaces.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    protected function registerPackage(Application $app)
    {
        $namespace = $this->name();

        $lang = $this->path($this->config('addon.paths.lang', 'lang'));
        if (is_dir($lang)) {
            $app['translator']->addNamespace($namespace, $lang);
        }

        $view = $this->path($this->config('addon.paths.views', 'views'));
        if (is_dir($view)) {
            $app['view']->addNamespace($namespace, $view);
        }

        $spec = $this->path($this->config('addon.paths.specs', 'specs'));
        if (is_dir($spec)) {
            $app['specs']->addNamespace($namespace, $spec);
        }
    }
}

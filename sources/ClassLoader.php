<?php

namespace Jumilla\Addomnipot\Laravel;

use Jumilla\Addomnipot\Laravel\Environment as AddonEnvironment;

class ClassLoader
{
    /**
     * @var static
     */
    protected static $instance;

    /**
     * @param array $addons
     */
    public static function register($addons)
    {
        static::$instance = new static($addons);

        // TODO check addon configuration

        spl_autoload_register([static::$instance, 'load'], true, false);
    }

    /**
     */
    public static function unregister()
    {
        if (static::$instance) {
            spl_autoload_unregister([static::$instance, 'load']);
        }
    }

    protected $env;

    protected $addons;

    /**
     * @param Jumilla\Addomnipot\Laravel\Environment $env
     * @param array $addons
     */
    public function __construct(AddonEnvironment $env, array $addons)
    {
        $this->env = $env;
        $this->addons = $addons;
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    public function load($className)
    {
        foreach ($this->addons as $addon) {
            $namespace = $addon->phpNamespace();

            $namespacePrefix = $namespace ? $namespace.'\\' : '';

            // アドオンの名前空間下のクラスでないなら
            if (!starts_with($className, $namespacePrefix)) {
                continue;
            }

            // 名前空間を削る
            $relativeClassName = substr($className, strlen($namespacePrefix));

            // クラスの相対パスを作成する（PSR-4）
            $relativePath = $this->env->classToPath($relativeClassName);

            // 全ディレクトリ下を探索する (PSR-4)
            foreach ($addon->config('addon.directories') as $directory) {
                $path = $addon->path($directory.'/'.$relativePath);
                if (file_exists($path)) {
                    require_once $path;

                    return true;
                }
            }
        }

        return false;
    }
}

<?php

namespace Jumilla\Addomnipot\Laravel;

use Illuminate\Filesystem\Filesystem;

class Directory
{
    /**
     * @param string $name
     *
     * @return string
     */
    public static function path($name = null)
    {
        if ($name !== null) {
            return static::path().'/'.$name;
        } else {
            return app('path.base').'/'.app('config')->get('addon.path', 'addons');
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public static function exists($name)
    {
        return is_dir(static::path($name));
    }

    /**
     * @param string $relativeClassName
     *
     * @return string
     */
    public static function classToPath($relativeClassName)
    {
        return str_replace('\\', '/', $relativeClassName).'.php';
    }

    /**
     * @param string $relativePath
     *
     * @return mixed
     */
    public static function pathToClass($relativePath)
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
    public static function addons()
    {
        $files = new Filesystem();

        $addonsDirectoryPath = static::path();

        // make addons/
        if (!$files->exists($addonsDirectoryPath)) {
            $files->makeDirectory($addonsDirectoryPath);
        }

        $addons = [];
        foreach ($files->directories($addonsDirectoryPath) as $dir) {
            $addon = Addon::create($dir);

            $addons[$addon->name()] = $addon;
        }

        return $addons;
    }
}

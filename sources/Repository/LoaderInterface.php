<?php

namespace Jumilla\Addomnipot\Laravel\Repository;

interface LoaderInterface
{
    /**
     * Load the given configuration group.
     *
     * @param string $group
     * @param string $namespace
     *
     * @return array
     */
    public function load($group, $namespace = null);

    /**
     * Determine if the given configuration group exists.
     *
     * @param string $group
     * @param string $namespace
     *
     * @return bool
     */
    public function exists($group, $namespace = null);

    /**
     * Add a new namespace to the loader.
     *
     * @param string $namespace
     * @param string $hint
     */
    public function addNamespace($namespace, $hint);

    /**
     * Returns all registered namespaces with the config
     * loader.
     *
     * @return array
     */
    public function getNamespaces();
}

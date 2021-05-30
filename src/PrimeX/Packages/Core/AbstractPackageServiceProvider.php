<?php

namespace PrimeX\Packages\Core;

use Illuminate\Support\ServiceProvider;

/**
 * Class AbstractPackageServiceProvider
 *
 * @package PrimeX\Packages\Core
 */
abstract class AbstractPackageServiceProvider extends ServiceProvider
{
    protected $loadWebRoutes = false;

    protected $loadApiRoutes = true;

    public $bindings = [];
    public $singletons = [];
    public $commands = [];

    /**
     * @return string
     */
    abstract protected function getName(): string;

    /**
     * @return string
     */
    abstract protected function getNamespace(): string;

    /**
     * Perform post-registration booting of services.
     *
     * @throws \ReflectionException
     */
    public function boot()
    {
        $rootDir = $this->getRootDir();
        $baseRoutesFolder = $rootDir . '/Http/Routes/';

        if ($this->loadWebRoutes) {
            $this->loadRoutesFrom($baseRoutesFolder . 'web.php');
        }

        if ($this->loadApiRoutes) {
            $this->loadRoutesFrom($baseRoutesFolder . 'api.php');
        }

        $this->loadMigrationsFrom($rootDir . '/Migrations');
        $this->loadViewsFrom($rootDir . '/Views', $this->getName());

        $this->commands($this->commands);
    }

    /**
     * @return string
     *
     * @throws \ReflectionException
     */
    protected function getRootDir(): string
    {
        $rc = new \ReflectionClass(get_class($this));

        return realpath(dirname($rc->getFileName()) . '/../');
    }

    /**
     * @param string $path
     */
    protected function loadRoutesFrom($path)
    {
        $middleware = [];
        // TODO: could load default middlewares here
        $this->loadRoutes($path, $middleware);
    }

    /**
     * @param string $path
     * @param array $middlewares
     * @param string|null $prefix
     */
    protected function loadRoutes(string $path, array $middlewares, ?string $prefix = null)
    {
        $this->app->router->group([
            'namespace'  => $this->getNamespace(),
            'middleware' => $middlewares,
            'prefix'     => $prefix,
            'as'         => $prefix ? $prefix . '.' : null,
        ], function ($router) use ($path) {
            require($path);
        });
    }
}

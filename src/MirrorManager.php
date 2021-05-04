<?php

namespace Mirror;

use Illuminate\Support\Manager;
use Mirror\Mirrors\FreshdeskMirror;

class MirrorManager extends Manager
{
    /**
     * The array of resolved mirrors.
     *
     * @var array
     */
    protected $drivers = [];

    /**
     * Reflect user profiles to all defined mirrors.
     *
     * @param $user
     * @return void
     */
    public function reflect($user)
    {
        foreach (config('mirror.mirrors') as $item => $config) {
            $this->mirror($item)->reflect($user);
        }
    }

    /**
     * Return a mirror provider instance.
     *
     * @param  string|null  $name
     * @return array
     */
    public function mirror($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        if (! isset($this->drivers[$name])) {
            $this->drivers[$name] = $this->resolve($name);
        }

        return $this->drivers[$name];
    }

    /**
     * Resolve a mirror provider.
     *
     * @param  string  $name
     * @return \Mirror\Contracts\MirrorContract
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        return $this->createDriver($name)->configure($config);
    }

    /**
     * Get the mirror configuration.
     *
     * @param  string  $name
     * @return array
     */
    protected function getConfig($name)
    {
        if (! is_null($name) && $name !== 'null') {
            return $this->app['config']["mirror.mirrors.{$name}"];
        }

        return ['driver' => 'null'];
    }

    /**
     * Dynamically pass calls to the default driver.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->mirror()->$method(...$parameters);
    }

    /**
     * @return string
     */
    public function getDefaultDriver()
    {
        return 'null';
    }

    /**
     * Create Freshdesk driver.
     *
     * return \Mirror\Contracts\MirrorContract
     */
    public function createFreshdeskDriver()
    {
        return new FreshdeskMirror();
    }
}

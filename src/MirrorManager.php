<?php

namespace Mirror;

use Illuminate\Contracts\Bus\Dispatcher as Bus;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Manager;
use InvalidArgumentException;

class MirrorManager extends Manager
{
    /**
     * The default mirror to reflect to.
     *
     * @var string
     */
    protected $defaultMirror = 'null';

    /**
     * The array of resolved mirrors.
     *
     * @var array
     */
    protected $drivers = [];

    /**
     * Reflect to the given mirrors.
     *
     * @param  \Illuminate\Support\Collection|array|mixed  $reflectables
     * @param  array|null  $mirrors
     * @return void
     */
    public function reflect($reflectables, array $mirrors = null)
    {
        (new Reflector(
            $this, $this->container->make(Bus::class), $this->container->make(Dispatcher::class), $this->getConfig())
        )->reflect($reflectables, $mirrors);
    }

    /**
     * Reflect the given reflectables immediately.
     *
     * @param  \Illuminate\Support\Collection|array|mixed  $reflectables
     * @param  array|null  $mirrors
     * @return void
     */
    public function reflectNow($reflectables, array $mirrors = null)
    {
        (new Reflector(
            $this, $this->container->make(Bus::class), $this->container->make(Dispatcher::class), $this->getConfig())
        )->reflectNow($reflectables, $mirrors);
    }

    /**
     * Get the configuration.
     *
     * @return array
     */
    protected function getConfig()
    {
        return $this->container['config']['mirror'];
    }

    /**
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->defaultMirror;
    }

    /**
     * Create an instance of the database driver.
     *
     * @return \Mirror\Mirrors\FreshdeskMirror
     */
    protected function createFreshdeskDriver()
    {
        return $this->container->make(Mirrors\FreshdeskMirror::class);
    }

    /**
     * Create a new driver instance.
     *
     * @param  string  $driver
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function createDriver($driver)
    {
        try {
            return parent::createDriver($driver);
        } catch (InvalidArgumentException $e) {
            if (class_exists($driver)) {
                return $this->container->make($driver);
            }

            throw $e;
        }
    }
}

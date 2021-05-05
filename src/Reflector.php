<?php

namespace Mirror;

use Illuminate\Database\Eloquent\Collection as ModelCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Mirror\Events\MirrorReflecting;

class Reflector
{
    /**
     * The mirror manager instance.
     *
     * @var \Mirror\MirrorManager
     */
    protected $manager;

    /**
     * The Bus dispatcher instance.
     *
     * @var \Illuminate\Contracts\Bus\Dispatcher
     */
    protected $bus;

    /**
     * The event dispatcher.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * Mirrors configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * Create a new Reflector instance.
     *
     * @param \Mirror\MirrorManager $manager
     * @param \Illuminate\Contracts\Bus\Dispatcher $bus
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     * @param array $config
     * @return void
     */
    public function __construct($manager, $bus, $events, array $config)
    {
        $this->bus = $bus;
        $this->config = $config;
        $this->events = $events;
        $this->manager = $manager;
    }

    /**
     * Reflect to the given mirrors.
     *
     * @param \Illuminate\Support\Collection|array|mixed $reflectables
     * @param array|null $mirrors
     * @return void
     */
    public function reflect($reflectables, array $mirrors = null)
    {
        return $this->queueReflection(
            $this->formatReflectables($reflectables), $this->collectMirrors($mirrors)
        );
    }

    /**
     * Reflect immediately.
     *
     * @param \Illuminate\Support\Collection|array|mixed $reflectables
     * @param array|null $mirrors
     * @return void
     */
    public function reflectNow($reflectables, array $mirrors = null)
    {
        $reflectables = $this->formatReflectables($reflectables);

        foreach ($reflectables as $reflectable) {
            foreach ($this->collectMirrors($mirrors) as $mirror) {
                $this->sendToMirror($reflectable, $mirror);
            }
        }
    }

    /**
     * Reflect the given reflectable to a mirror.
     *
     * @param  mixed  $reflectable
     * @param  string  $mirror
     * @return void
     */
    protected function sendToMirror($reflectable, $mirror)
    {
        if (! $this->shouldReflect($reflectable, $mirror)) {
            return;
        }

        $response = $this->manager->driver($mirror)->reflect($reflectable);
    }

    /**
     * Determines if the reflection should happen.
     *
     * @param  mixed  $reflectable
     * @param  string  $mirror
     * @return bool
     */
    protected function shouldReflect($reflectable, $mirror)
    {
        return $this->events->until(
                new MirrorReflecting($reflectable, $mirror)
            ) !== false;
    }

    /**
     * Queue the given reflection instances.
     *
     * @param  mixed  $reflectables
     * @param array|null $mirrors
     * @return void
     */
    protected function queueReflection($reflectables, array $mirrors = null)
    {
        $reflectables = $this->formatReflectables($reflectables);

        foreach ($reflectables as $reflectable) {
            foreach ($mirrors as $mirror) {
                $this->bus->dispatch(
                    (new SendQueuedReflections($reflectable, [$mirror]))
                        ->onQueue($this->config['queue'])
                        ->through([])
                );
            }
        }
    }

    /**
     * Format the reflectables into a Collection / array if necessary.
     *
     * @param  mixed  $reflectables
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    protected function formatReflectables($reflectables)
    {
        if (! $reflectables instanceof Collection && ! is_array($reflectables)) {
            return $reflectables instanceof Model
                ? new ModelCollection([$reflectables]) : [$reflectables];
        }

        return $reflectables;
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
     * Collect the mirrors to reflect to.
     *
     * @param $mirrors
     * @return array
     */
    protected function collectMirrors($mirrors): array
    {
        return Arr::wrap($mirrors ?? $this->config['mirrors']);
    }
}

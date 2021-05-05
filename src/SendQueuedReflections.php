<?php

namespace Mirror;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SendQueuedReflections implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The reflectable entities that should be reflected.
     *
     * @var \Illuminate\Support\Collection
     */
    public $reflectables;

    /**
     * All of the mirrors to reflect to.
     *
     * @var array
     */
    public $mirrors;

    /**
     * Create a new job instance.
     *
     * @param  \Mirror\Reflectable|\Illuminate\Support\Collection  $reflections
     * @param  array|null  $mirrors
     * @return void
     */
    public function __construct($reflections, array $mirrors = null)
    {
        $this->mirrors = $mirrors;
        $this->reflectables = $this->wrapReflectables($reflections);
    }

    /**
     * Wrap the reflectable(s) in a collection.
     *
     * @param  \Mirror\Reflectable|\Illuminate\Support\Collection  $reflections
     * @return \Illuminate\Support\Collection
     */
    protected function wrapReflectables($reflectables)
    {
        if ($reflectables instanceof Collection) {
            return $reflectables;
        } elseif ($reflectables instanceof Model) {
            return EloquentCollection::wrap($reflectables);
        }

        return Collection::wrap($reflectables);
    }

    /**
     * Reflect.
     *
     * @param  \Mirror\MirrorManager  $manager
     * @return void
     */
    public function handle(MirrorManager $manager)
    {
        $manager->sendNow($this->reflectables, $this->mirrors);
    }

    /**
     * Prepare the instance for cloning.
     *
     * @return void
     */
    public function __clone()
    {
        $this->reflectables = clone $this->reflectables;
    }
}

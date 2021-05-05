<?php

namespace Mirror;

use Illuminate\Support\Str;

trait Reflectable
{
    /**
     * Reflect method for Reflectable model.
     *
     * @param null $mirrors
     */
    public function reflect($mirrors = null)
    {
        app(MirrorManager::class)->reflect($this, $mirrors);
    }

    /**
     * Reflect method for Reflectable model.
     *
     * @param null $mirrors
     */
    public function reflectNow($mirrors = null)
    {
        app(MirrorManager::class)->reflect($this, $mirrors);
    }

    /**
     * Get the mirror routing information for the given driver.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function reflectTo($driver, $user)
    {
        if (method_exists($this, $method = 'reflectTo'.Str::studly($driver))) {
            return $this->{$method}($user);
        }

        return null;
    }
}

<?php

namespace Mirror;

use Illuminate\Support\Str;

trait Reflectable
{
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

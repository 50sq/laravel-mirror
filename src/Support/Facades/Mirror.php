<?php

namespace Mirror\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Mirror extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mirror';
    }
}

<?php

namespace Mirror\Mirrors;

use Mirror\Contracts\MirrorContract;

abstract class BaseMirror implements MirrorContract
{
    protected $config = [];

    /**
     * Configure the provider.
     *
     * @param array $config
     */
    public function configure(array $config)
    {
        $this->config = $config;
    }
}

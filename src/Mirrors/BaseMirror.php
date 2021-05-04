<?php

namespace Mirror\Mirrors;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mirror\Contracts\MirrorContract;

abstract class BaseMirror implements MirrorContract, ShouldQueue
{
    use InteractsWithQueue, Queueable;

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

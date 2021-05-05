<?php

namespace Mirror\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class MirrorReflecting
{
    use Queueable, SerializesModels;

    /**
     * The reflectable entity who will be reflected.
     *
     * @var mixed
     */
    public $reflectable;

    /**
     * The mirror name.
     *
     * @var string
     */
    public $mirror;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $reflectable
     * @param  string  $mirror
     * @return void
     */
    public function __construct($reflectable, $mirror)
    {
        $this->mirror = $mirror;
        $this->reflectable = $reflectable;
    }
}

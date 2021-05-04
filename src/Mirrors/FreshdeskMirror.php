<?php

namespace Mirror\Mirrors;

use Illuminate\Support\Facades\Http;
use Mirror\User;

class FreshdeskMirror extends BaseMirror
{
    public function reflect($user)
    {
        Http::asJson()->withToken($this->config['key'])->put(
            '/',
            (new User())->setRaw($user)->getRaw()
        );
    }
}

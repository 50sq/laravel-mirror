<?php

namespace Mirror\Mirrors;

use Illuminate\Support\Facades\Http;

class FreshdeskMirror extends BaseMirror
{
    public function reflect($user)
    {
        Http::asJson()->withToken($this->config['key'])->put(
            '/',
            [],
        );
    }
}

<?php

namespace Mirror\Mirrors;

use Illuminate\Support\Facades\Http;

class FreshdeskMirror extends BaseMirror
{
    public function reflect($reflectable)
    {
        if (! $reflectable->reflectTo('freshdesk')) {
            return;
        }

        $data = $reflectable->reflectTo('freshdesk');

        Http::asJson()->withToken($this->config['key'])->put(
            '/',
            $data
        );
    }
}

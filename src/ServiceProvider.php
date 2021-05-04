<?php

namespace Mirror;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Mirror\Support\Facades\Mirror;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mirror.php', 'mirror');

        $this->app->singleton('mirror', function ($app) {
            return new MirrorManager($app);
        });

        $this->app->alias('Mirror', Mirror::class);
    }
}

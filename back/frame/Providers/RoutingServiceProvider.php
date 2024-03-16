<?php

namespace Frame\Providers;

use Frame\Application;
use Frame\Routing\Router;

class RoutingServiceProvider
{
    public function boot(Application $app)
    {
        $app->singleton(Router::class);

        require $app->routesPath();
    }
}

<?php

namespace Frame\Bootstrappers;

use Frame\Application;

use Dotenv\Dotenv;

class LoadEnvironmentVariables
{
    /**
     * Load environment variables.
     *
     * @param  \Frame\Application $app
     * @return void
     */
    public function boot(Application $app): void
    {
        $path = $app->environmentPath();
        $fileName = $app->environmentFileName();

        Dotenv::createImmutable($path, $fileName)->load();
    }
}

<?php

/*
|------------------------------------------------------
| Instantiate the application.
|------------------------------------------------------
*/

$app = new \Frame\Application(dirname(__DIR__));

/*
|------------------------------------------------------
| Bootstrap the application.
|------------------------------------------------------
*/

$app->bootstrap();

/*
|------------------------------------------------------
| Return the application instance.
|------------------------------------------------------
*/

return $app;

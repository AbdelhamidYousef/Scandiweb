<?php

/*
|------------------------------------------------------
| Register auto-loader.
|------------------------------------------------------
*/

require __DIR__ . "/../vendor/autoload.php";

/*
|------------------------------------------------------
| Bootstrap the application.
|------------------------------------------------------
*/

$app = require_once __DIR__ . "/../bootstrap/app.php";

/*
|------------------------------------------------------
| Setup the database.
|------------------------------------------------------
*/

$app->setupDatabase(['products']);
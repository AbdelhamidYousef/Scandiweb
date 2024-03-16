<?php

return [
    'driver' => $_ENV['DRIVER'] ?? 'mysql',
    'host' => $_ENV['HOST'] ?? 'localhost',

    'dbname' => $_ENV['DB_NAME'] ?? 'scandiweb',

    'username' => $_ENV['USERNAME'] ?? 'root',
    'password' => $_ENV['PASSWORD'] ?? '',

    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    ]
];

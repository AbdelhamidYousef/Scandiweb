<?php

use Frame\Routing\Router;
use App\Controllers\ProductsController;

Router::get("api/products", [ProductsController::class, 'index']);
Router::post("api/products", [ProductsController::class, 'store']);
Router::delete("api/products", [ProductsController::class, 'massDelete']);

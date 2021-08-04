<?php

use Framework\Http\Router\RoutesCollection;
use App\Http\Controllers\DocController;

/** @var RoutesCollection $routes */

$routes->get('/doc', DocController::class)->name('doc');

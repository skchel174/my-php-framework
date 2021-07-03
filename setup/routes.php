<?php

use Framework\Http\Router\RoutesCollection;
use App\Http\Controllers\IndexController;

/** @var RoutesCollection $routes */

$routes->get('/', [IndexController::class, 'index']);
$routes->post('/{page}', [IndexController::class, 'page'])
    ->params(['page' => '[a-z]+'])
    ->name('home');
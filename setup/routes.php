<?php

use App\Http\Controllers\DocController;
use Framework\Http\Router\RoutesCollection;
use App\Http\Controllers\IndexController;

/** @var RoutesCollection $routes */

$routes->get('/doc', DocController::class)->name('doc');

$routes->get('/api', [IndexController::class, 'index']);
$routes->post('/{page}', [IndexController::class, 'page'])
    ->params(['page' => '[a-z]+'])
    ->name('home');

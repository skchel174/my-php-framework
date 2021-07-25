<?php

use App\Http\Controllers\GreetingController;
use Framework\Http\Router\RoutesCollection;
use App\Http\Controllers\IndexController;

/** @var RoutesCollection $routes */

$routes->get('/', [GreetingController::class, 'index'])->name('greeting');
$routes->get('/doc', [GreetingController::class, 'doc'])->name('doc');



$routes->get('/home', [IndexController::class, 'index']);
$routes->post('/{page}', [IndexController::class, 'page'])
    ->params(['page' => '[a-z]+'])
    ->name('home');

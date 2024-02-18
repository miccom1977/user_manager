<?php

use App\Controllers\UserController;
use App\Router;

// Utwórz router
$router = new Router();

// Routing dla użytkowników
$router->get('/users', [UserController::class, 'index']);

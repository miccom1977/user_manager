<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../defines.php';

use App\Config\Database;
use App\Models\User;
use App\Models\Group;
use App\Controllers\UserController;
use App\Controllers\GroupController;
use App\Router;
use App\Services\UserService;

// Prepare POD object
$db = new Database();
$pdo = $db->getConnection(); // get PDO

// Create User Model
$userModel = new User($pdo);

// Create UserService with User Model
$userService = new UserService($userModel);

// Create Controller with Service
$userController = new UserController($userService);

$router = new Router();

// Add Routes
$router->get('/', function() {
	include_once VIEWS_PATH . 'homepage.html';
});

$router->get('/users', [$userController, 'index']);
$router->post('/users/create', [$userController, 'create']);
$router->get('/users/{id}', [$userController, 'read']);
$router->post('/users/{id}', [$userController, 'update']);
$router->delete('/users/{id}', [$userController, 'delete']);

// Prepare handle
$router->handle($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../defines.php';

use App\Config\Database;
use App\Models\User;
use App\Models\Group;
use App\Controllers\UserController;
use App\Controllers\GroupController;
use App\Router;
use App\Services\GroupService;
use App\Services\UserService;

// Prepare POD object
$db = new Database();
$pdo = $db->getConnection(); // get PDO

// Create User Model
$userModel = new User($pdo);
// create Group Model
$groupModel = new Group($pdo);

// Create UserService with User Model
$userService = new UserService($userModel);
// Create GroupService with User Model
$groupService = new GroupService($groupModel);

// Create Controller with Service
$userController = new UserController($userService, $groupService);
// Create Controller with Service
$groupController = new GroupController($groupService);

$router = new Router();

// Add Routes
$router->get('/', function() {
	include_once VIEWS_PATH . 'users.html';
});

$router->get('/users', [$userController, 'index']);
$router->post('/users/create', [$userController, 'create']);
$router->get('/users/{id}', [$userController, 'read']);
$router->post('/users/{id}', [$userController, 'update']);
$router->delete('/users/{id}', [$userController, 'delete']);

$router->get('/groupList', function() {
	include_once VIEWS_PATH . 'groups.html';
});
$router->get('/groups', [$groupController, 'index']);
$router->post('/groups/create', [$groupController, 'create']);
$router->get('/groups/{id}', [$groupController, 'read']);
$router->post('/groups/{id}', [$groupController, 'update']);
$router->delete('/groups/{id}', [$groupController, 'delete']);

// Prepare handle
$router->handle($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

<?php

use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Core\Router;
use App\Core\Request;

session_start();

require 'bootstrap.php';

$router = new Router();
$router->get('/', [HomeController::class, 'index']);
$router->get('products', [ProductController::class, 'index']);
$router->post('products', [ProductController::class, 'create']);
$router->put('products', [ProductController::class, 'update']);
$router->delete('products', [ProductController::class, 'destroy']);
$router->post('products/copy', [ProductController::class, 'copy']);

$router->get('categories', [CategoryController::class, 'index']);
$router->post('categories', [CategoryController::class, 'create']);
$router->put('categories', [CategoryController::class, 'update']);
$router->delete('categories', [CategoryController::class, 'destroy']);

$router->call(Request::uri(), Request::method());

if (isset($_SESSION['_flash'])) {
    unset($_SESSION['_flash']);
}

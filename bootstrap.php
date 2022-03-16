<?php
require 'vendor/autoload.php';

use App\Core\Container;
use App\Core\Database\Database;
use App\Core\Database\Connection;

define('ROOT_DIR', __DIR__);

$container = Container::getInstance();

$container->bind('config', require 'config.php');
$container->bind(Database::class, new Database(
    Connection::make($container->get('config')['database'])
));

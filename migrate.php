<?php

use App\Core\Database\Database;

require 'bootstrap.php';

$sql = file_get_contents('database.sql');
$container->get(Database::class)
    ->exec($sql);

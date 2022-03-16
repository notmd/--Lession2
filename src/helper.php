<?php

function dd(...$data)
{
    var_dump(...$data);

    die;
}

function view(string $view, array $data = [], ?string $layout = 'default')
{
    unset($data['view']);
    extract($data);
    if ($layout === null) {
        require "Views/$view.view.php";
    } else {
        require "Views/layout/$layout.view.php";
    }
}

function redirect(string $location)
{
    header('Location: ' . $location);
    exit;
}

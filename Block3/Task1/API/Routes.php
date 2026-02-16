<?php

namespace API;

use API\App;


class Routes
{
    public static function get(string $url, callable $callback): void
    {
        App::addGetController($url, $callback);
    }
    public static function post(string $url, callable $callback): void
    {
        App::addPostController($url, $callback);
    }
    public static function pageNotFound(callable $callback): void
    {
        App::setNotFoundController($callback);
    }
}
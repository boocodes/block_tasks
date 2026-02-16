<?php

namespace Task2\Core;


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

    public static function patch(string $url, callable $callback): void
    {
        App::addPatchController($url, $callback);
    }

    public static function delete(string $url, callable $callback): void
    {
        App::addDeleteController($url, $callback);
    }

    public static function pageNotFound(callable $callback): void
    {
        App::setNotFoundController($callback);
    }
}
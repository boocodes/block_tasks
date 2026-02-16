<?php

namespace Task2\Core;
require_once 'Api.php';

use Task2\Core\Request;

class App
{
    private function __construct()
    {
    }

    private static array $headers;
    private static false|string $inputData;
    private static array $pageNotFoundController = [];
    private static array $postControllers = [];
    private static array $getControllers = [];

    public static function setNotFoundController(callable $callback): void
    {
        self::$pageNotFoundController[0] = $callback;
    }

    public static function addPostController(string $url, callable $callback): void
    {
        self::$postControllers[$url] = $callback;
    }

    public static function addGetController(string $url, callable $callback): void
    {
        self::$getControllers[$url] = $callback;
    }

    public static function run(): void
    {
        self::$headers = $_SERVER;
        self::$inputData = file_get_contents('php://input');
        $request = new Request(self::$headers, self::$inputData);
        $urlHandled = false;
        if (self::$headers['REQUEST_METHOD'] === 'GET') {
            foreach (self::$getControllers as $controller => $method) {
                if ($controller == self::$headers['REQUEST_URI']) {
                    $method($request);
                    $urlHandled = true;
                    break;
                }
            }
        }

        if (self::$headers['REQUEST_METHOD'] === 'POST') {
            foreach (self::$postControllers as $controller => $method) {
                if ($controller == self::$headers['REQUEST_URI']) {
                    $method($request);
                    $urlHandled = true;
                    break;
                }
            }
        }
        if (!$urlHandled) {
            if (isset(self::$pageNotFoundController[0])) {
                self::$pageNotFoundController[0]($request);
            }
        }
    }
}
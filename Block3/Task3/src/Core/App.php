<?php

namespace Task3\Core;
require_once 'Api.php';

use Task3\Core\Request;

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
    private static array $deleteControllers = [];
    private static array $patchControllers = [];

    private static function applyPattern(string $url): string
    {
        $pattern = preg_replace('/\{\$([a-zA-Z0-9_]+)\}/', '(?P<$1>\d+)', $url);
        return '#^' . $pattern . '$#';
    }

    public static function setNotFoundController(callable $callback): void
    {
        self::$pageNotFoundController[0] = $callback;
    }

    public static function addPostController(string $url, callable $callback): void
    {
        self::$postControllers[$url] = [
            'url' => $url,
            'callback' => $callback,
            'pattern' => self::applyPattern($url),
        ];
    }
    public static function addPatchController(string $url, callable $callback): void
    {
        self::$patchControllers[$url] = [
          'url' => $url,
          'callback' => $callback,
          'pattern' => self::applyPattern($url),
        ];
    }
    public static function addDeleteController(string $url, callable $callback): void
    {
        self::$deleteControllers[$url] = [
            'url' => $url,
            'callback' => $callback,
            'pattern' => self::applyPattern($url),
        ];
    }

    public static function addGetController(string $url, callable $callback): void
    {
        self::$getControllers[$url] = [
            'url' => $url,
            'callback' => $callback,
            'pattern' => self::applyPattern($url),
        ];
    }

    public static function run(): void
    {
        self::$headers = $_SERVER;
        self::$inputData = file_get_contents('php://input');
        $request = new Request(self::$headers, self::$inputData);
        $requestUrl = strtok(self::$headers['REQUEST_URI'], '?');
        $urlHandled = false;
        if (self::$headers['REQUEST_METHOD'] === 'GET') {
            foreach (self::$getControllers as $controller) {
                if(preg_match($controller['pattern'], $requestUrl, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    if(!empty($params)) {
                        $controller['callback']($request, ...array_values($params));
                    }
                    else
                    {
                        $controller['callback']($request);
                    }
                    $urlHandled = true;
                    break;
                }
            }
        }
        if(self::$headers['REQUEST_METHOD'] === 'POST') {
            foreach (self::$postControllers as $controller) {
                if(preg_match($controller['pattern'], $requestUrl, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    if(!empty($params)) {
                        $controller['callback']($request, ...array_values($params));
                    }
                    else
                    {
                        $controller['callback']($request);
                    }
                    $urlHandled = true;
                    break;
                }
            }
        }
        if(self::$headers['REQUEST_METHOD'] === 'DELETE') {
            foreach (self::$deleteControllers as $controller) {
                if(preg_match($controller['pattern'], $requestUrl, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    if(!empty($params))
                    {
                        $controller['callback']($request, ...array_values($params));
                    }
                    else
                    {
                        $controller['callback']($request);
                    }
                    $urlHandled = true;
                    break;
                }
            }
        }
        if(self::$headers['REQUEST_METHOD'] === 'PATCH') {
            foreach (self::$patchControllers as $controller) {
                if(preg_match($controller['pattern'], $requestUrl, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    if(!empty($params))
                    {
                        $controller['callback']($request, ...array_values($params));
                    }
                    else
                    {
                        $controller['callback']($request);
                    }
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
<?php

namespace Task5\Core;
require_once 'Api.php';

use Task5\Core\Request;

class App
{
    private function __construct()
    {
    }

    private static array $headers;
    private static string $rootStoragePath = __DIR__ . '/../';
    private static array $inputJson;
    private static array $inputQuery;
    private static string $bearerToken;
    private static array $pageNotFoundController = [];
    private static array $postControllers = [];
    private static array $getControllers = [];
    private static array $deleteControllers = [];
    private static array $patchControllers = [];

    public static function getHeaders(): array
    {
        return self::$headers;
    }
    public static function getInputJson(): array
    {
        return self::$inputJson;
    }
    public static function getInputQuery(): array
    {
        return self::$inputQuery;
    }
    public static function getRootStoragePath(): string
    {
        return self::$rootStoragePath;
    }

    public static function getBearerToken(): string
    {
        return self::$bearerToken;
    }

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

    public static function run(array $config): void
    {
        self::$headers = $_SERVER;
        self::$bearerToken = $config['API_KEY'];
        self::$inputJson = json_decode(file_get_contents('php://input'), true);
        var_dump(self::$inputJson);
        self::$inputQuery = [];
        parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?? '', self::$inputQuery);


        $request = new Request(self::$headers, self::$inputJson, self::$inputQuery);
        $requestUrl = strtok(self::$headers['REQUEST_URI'], '?');
        $urlHandled = false;
        if (self::$headers['REQUEST_METHOD'] === 'GET') {
            foreach (self::$getControllers as $controller) {
                if (preg_match($controller['pattern'], $requestUrl, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    if (!empty($params)) {
                        $controller['callback']($request, ...array_values($params));
                    } else {
                        $controller['callback']($request);
                    }
                    $urlHandled = true;
                    break;
                }
            }
        }
        if (self::$headers['REQUEST_METHOD'] === 'POST') {
            foreach (self::$postControllers as $controller) {
                if (preg_match($controller['pattern'], $requestUrl, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    if (!empty($params)) {
                        $controller['callback']($request, ...array_values($params));
                    } else {
                        $controller['callback']($request);
                    }
                    $urlHandled = true;
                    break;
                }
            }
        }
        if (self::$headers['REQUEST_METHOD'] === 'DELETE') {
            foreach (self::$deleteControllers as $controller) {
                if (preg_match($controller['pattern'], $requestUrl, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    if (!empty($params)) {
                        $controller['callback']($request, ...array_values($params));
                    } else {
                        $controller['callback']($request);
                    }
                    $urlHandled = true;
                    break;
                }
            }
        }
        if (self::$headers['REQUEST_METHOD'] === 'PATCH') {
            foreach (self::$patchControllers as $controller) {
                if (preg_match($controller['pattern'], $requestUrl, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    if (!empty($params)) {
                        $controller['callback']($request, ...array_values($params));
                    } else {
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
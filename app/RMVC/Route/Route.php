<?php

namespace App\RMVC\Route;

class Route
{
    private static array $routesGet = [];
    private static array $routesPost = [];
    private static array $routesPatch = [];
    private static array $routesPut = [];
    private static array $routesDelete = [];

    public static function getRoutesGet(): array
    {
        return self::$routesGet;
    }

    public static function getRoutesPost(): array
    {
        self::csrf_token_verify();

        return self::$routesPost;
    }

    public static function getRoutesPatch(): array
    {
        self::csrf_token_verify();

        return self::$routesPatch;
    }

    public static function getRoutesPut(): array
    {
        self::csrf_token_verify();

        return self::$routesPut;
    }

    public static function getRoutesDelete(): array
    {
        self::csrf_token_verify();

        return self::$routesDelete;
    }

    public static function get(string $route, array $controller): RouteConfiguration
    {
        $routeConfiguration = new RouteConfiguration($route, $controller[0], $controller[1]);
        self::$routesGet[] = $routeConfiguration;

        return $routeConfiguration;
    }

    public static function post(string $route, array $controller): RouteConfiguration
    {
        $routeConfiguration = new RouteConfiguration($route, $controller[0], $controller[1]);
        self::$routesPost[] = $routeConfiguration;

        return $routeConfiguration;
    }

    public static function patch(string $route, array $controller): RouteConfiguration
    {
        $routeConfiguration = new RouteConfiguration($route, $controller[0], $controller[1]);
        self::$routesPatch[] = $routeConfiguration;

        return $routeConfiguration;
    }

    public static function put(string $route, array $controller): RouteConfiguration
    {
        $routeConfiguration = new RouteConfiguration($route, $controller[0], $controller[1]);
        self::$routesPut[] = $routeConfiguration;

        return $routeConfiguration;
    }

    public static function delete(string $route, array $controller): RouteConfiguration
    {
        $routeConfiguration = new RouteConfiguration($route, $controller[0], $controller[1]);
        self::$routesDelete[] = $routeConfiguration;

        return $routeConfiguration;
    }

    public static function redirect(string $url, ?array $params = null): void
    {
        if ($params) {
            $url = self::replacePlaceholders($url, $params);
        }

        header('Location: ' . $url);
    }

    public static function redirectByRouteName(string $routeName, ?array $params = []): void
    {  
        $route = self::getRoutePathByName($routeName);

        if ($route) {
            self::redirect($route, $params);
        } else {
            trigger_error("Route $routeName does not exist.");
            die();
        }
    }

    public static function getRoutePathByName(string $name): ?string
    {
        $routes = array_merge(self::$routesGet, self::$routesPost);

        foreach ($routes as $route) {
            if ($route->getName() === $name) {
                return $route->route;
            }
        }

        return null;
    }

    private static function replacePlaceholders(string $string, array $values): string
    {
        foreach ($values as $key => $value) {
            $placeholder = '{' . $key . '}';
            var_dump($placeholder);
            $string = str_replace($placeholder, $value, $string);
        }

        return $string;
    }

    private static function csrf_token_verify(): void
    {
        if (
            !isset($_POST['csrf'])
            || !isset($_SESSION['csrf'])
            || $_POST['csrf'] !== $_SESSION['csrf']
        ) {
            header('HTTP/1.1 419 Page Expired');

            die();
        }

        if (isset($_SESSION['csrf'])) {
            unset($_SESSION['csrf']);
        }
    }
}

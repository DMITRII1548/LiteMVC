<?php

namespace App\RMVC;

use App\RMVC\Route\Route;
use App\RMVC\Route\RouteDispatcher;

class App
{
    public static function run(): void
    {
        $requestMethod = ucfirst(strtolower($_SERVER['REQUEST_METHOD']));

        if ($requestMethod === 'Post' && isset($_POST['_method'])) {
            $requestMethod = ucfirst(strtolower($_POST['_method']));
        }

        $methodName = 'getRoutes' . $requestMethod;

        foreach (Route::$methodName() as $route) {
            $routeDispatcher = new RouteDispatcher($route);
            $routeDispatcher->process();
        }
    }
}

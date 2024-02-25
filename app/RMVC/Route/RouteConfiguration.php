<?php

namespace App\RMVC\Route;

class RouteConfiguration
{
    public string $route;
    public string $controller;
    public string $action;
    private string $name;
    private string $middleware = '';

    public function __construct(
        string $route, 
        string $controller, 
        string $action
    ) {
        $this->route = $route;
        $this->controller = $controller;
        $this->action = $action;
    }

    public function name(string $name): RouteConfiguration
    {
        $this->name = $name;
        
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function middleware(string $middleware): RouteConfiguration 
    {
        $this->middleware = $middleware;

        return $this;
    }

    public function getMiddleware(): string
    {
        return $this->middleware;
    }
}

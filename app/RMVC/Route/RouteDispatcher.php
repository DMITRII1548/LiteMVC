<?php

namespace App\RMVC\Route;

use App\RMVC\Middleware\Middleware;

class RouteDispatcher 
{
    private string $requestUri = '/';

    private array $paramMap = [];

    private array $paramRequestMap = [];

    private RouteConfiguration $routeConfiguration;

    public function __construct(RouteConfiguration $routeConfiguration)
    {
        $this->routeConfiguration = $routeConfiguration;
    }

    public function process(): void
    {
        $this->saveRequestUri();
        $this->setParamMap();
        $this->makeRegexRequest();
        $this->run();
    }

    private function saveRequestUri(): void
    {
        if ($_SERVER['REQUEST_URI'] !== '/') {
            $this->requestUri = $this->clean($_SERVER['REQUEST_URI']);
            $this->routeConfiguration->route = $this->clean($this->routeConfiguration->route);
        }
    }

    private function clean(string $str): string 
    {
        return preg_replace('/(^\/)|(\/$)/', '', $str);
    }

    private function setParamMap(): void
    {
        $routeArray = explode('/', $this->routeConfiguration->route);
        
        foreach ($routeArray as $paramKey => $param) {
            if (preg_match('/{.*}/', $param)) {
                $this->paramMap[$paramKey] = preg_replace('/(^{)|(}$)/', '', $param); 
            }
        }
    }

    private function makeRegexRequest(): void
    {
        $requestUriArray = explode('/', $this->requestUri);

        foreach ($this->paramMap as $paramKey => $param) {
            if (!isset($requestUriArray)) {
                return;
            }
            
            $this->paramRequestMap[$param] = $requestUriArray[$paramKey];
            $requestUriArray[$paramKey] = '{.*}';
        }

        $this->requestUri = implode('/', $requestUriArray);
        $this->prepareRegex();
    }

    private function prepareRegex(): void
    {
        $this->requestUri = str_replace('/', '\/', $this->requestUri);
    }

    private function allowRoute(): void
    {
        $middleware = $this->routeConfiguration->getMiddleware();

        if ($middleware) {
            Middleware::run($middleware);
        }
    }

    private function run(): void
    {
        if (preg_match("/$this->requestUri/", $this->routeConfiguration->route)) {
            $this->allowRoute();
            $this->render();
        }
    }

    private function render(): void
    {
        $ClassName = $this->routeConfiguration->controller;
        $action = $this->routeConfiguration->action;

        echo (new $ClassName)->$action(...$this->paramRequestMap);

        die();
    }

    
}
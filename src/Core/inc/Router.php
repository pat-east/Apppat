<?php

include_once('Route.php');
include_once('HttpResult.php');
include_once('HttpRequestContext.php');

class Router {

    /** @var Route[] */
    var array $routes = [];

    public function __construct() {

    }

    public function init(): void {
        Helper::IncludeOnce(Defaults::ABSPATH . '/Core/inc/Routes', true);
        Helper::IncludeOnce(Defaults::ABSPATH . '/Core/inc/HttpResults', true);
    }

    public function registerRoute(Route $route): void {
        $this->routes[] = $route;
    }

    public function route(): Route {

        $requestUri = '/';
        if(isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
        }

        foreach($this->routes as $route) {
            if($route->matchesRequestUri($requestUri)) {
                // http_response_code(200); // Not necessary
                return $route;
            }
        }

        $this->routeFallback($requestUri);
    }

    public function routeFallback(string $requestUri): never {
        if(!str_ends_with($requestUri, '/')) {
            header('location: ' . $requestUri . '/');
            exit;
        }

        http_response_code(404);
        // TODO refactor me. This should also return a Route-instance instead of including some hard-coded 404.php
        include_once(Defaults::ABSPATH . '/Views/404.php');
        exit;
    }

}
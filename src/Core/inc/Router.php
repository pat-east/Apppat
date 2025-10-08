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

        return $this->routeFallback($requestUri);
    }

    public function routeFallback(string $requestUri): Route {
        if(!str_ends_with($requestUri, '/')) {
            header('location: ' . $requestUri . '/');
            exit;
        }

        http_response_code(404);
        return new ViewRoute('/404', 'Status404View');
    }

}
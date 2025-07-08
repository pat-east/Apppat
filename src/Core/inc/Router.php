<?php

include_once('Route.php');

class Router {

    /** @var Route[] */
    var array $routes = [];

    public function __construct() {

    }

    function init(): void {
        $this->initRoutes();
    }

    function initRoutes(): void {
        $this->routes[] = new Route('/', '/views/startseite.php');
        $this->routes[] = new Route('/about', '/views/about.php');
        $this->routes[] = new Route('/datenschutz', '/views/datenschutz.php');
        $this->routes[] = new Route('/impressum/', '/views/impressum.php');
    }

    function route(): void {

//        throw new Error('Something went wrong');
//        trigger_error('Something went wrong!', E_USER_ERROR);
//        sleep(61);

        $requestUri = '/';
        if(isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
        }

        foreach($this->routes as $route) {
            if($route->matchesRequestUri($requestUri)) {
                include_once(Defaults::ABSPATH . $route->view);
                exit;
            }
        }

        $this->routeFallback($requestUri);
    }

    function routeFallback(string $requestUri): void {
        if(!str_ends_with($requestUri, '/')) {
            header('location: ' . $requestUri . '/');
            exit;
        }

        http_response_code(404);
        include_once(Defaults::ABSPATH . '/views/404.php');
    }

}
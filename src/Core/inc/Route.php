<?php

class Route {

    var string $route;
    var string $viewClassName;

    var array $args = [];

    /**
     * @param class-string $viewClassName
     */
    public function __construct(string $route, string $viewClassName) {
        $this->route = $route;
        $this->viewClassName = $viewClassName;
    }

    public function getRequestArguments(): array {
        return $this->args;
    }

    public function matchesRequestUri($requestURI): bool {

        if(!$requestURI) { return false; }

        if($requestURI == $this->route) {
            return true;
        }

        if($requestURI == $this->route . '/') {
            return true;
        }

        return false;
    }

}
<?php

class Route {

    var string $route;
    var string $viewClassName;

    /**
     * @param class-string $viewClassName
     */
    function __construct(string $route, string $viewClassName) {
        $this->route = $route;
        $this->viewClassName = $viewClassName;
    }

    function matchesRequestUri($requestURI): bool
    {
        if($requestURI == $this->route) {
            return true;
        }
        if($requestURI == $this->route . '/') {
            return true;
        }

        return false;
    }

}
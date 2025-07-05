<?php

class Route {

    var string $route;
    var string $view;

    function __construct(string $route, string $view) {
        $this->route = $route;
        $this->view = $view;
    }

    function matchesRequestUri($requestURI) {
        if($requestURI == $this->route) {
            return true;
        }
        if($requestURI == $this->route . '/') {
            return true;
        }

        return false;
    }

}
<?php

use MongoDB\BSON\Regex;

class RegexRoute extends Route {



    /**
     * @param class-string $viewClassName
     */
    function __construct(string $route, string $viewClassName) {
        parent::__construct($route, $viewClassName);
    }

    function matchesRequestUri($requestURI): bool {


        if(preg_match($this->route, $requestURI, $this->args)) {
            return true;
        }


        if($requestURI == $this->route . '/') {
            return true;
        }

        return false;
    }

}
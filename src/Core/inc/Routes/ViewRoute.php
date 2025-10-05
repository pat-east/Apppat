<?php

class ViewRoute extends Route {
    /**
     * @param class-string $viewClassName
     */
    public function __construct(string $route, string $viewClassName) {
        parent::__construct($route, function() use($viewClassName) { return new ViewHttpResult($viewClassName); }, HttpMethod::Get);
    }

    public function _matchesRequestUri(string $requestURI): bool {

        if($requestURI == $this->route) {
            return true;
        }

        if($requestURI == $this->route . '/') {
            return true;
        }

        return false;
    }
}
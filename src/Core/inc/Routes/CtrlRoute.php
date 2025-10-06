<?php

class CtrlRoute extends Route {

    /**
     * @param string $route
     * @param Callable(object, string): HttpRequestContext $callUserFunc
     *  Argument for call_user_func containing instance [of controller] and method-name [defined inside controller].
     */
    public function __construct(string $route, $callUserFunc) {
        parent::__construct(
            $route,
            function() use($callUserFunc) {
                return call_user_func($callUserFunc, new HttpRequestContext($this));
            },
            HttpMethod::Post);
    }

    protected function _matchesRequestUri(string $requestURI): bool {
        return parent::_matchesAbsoluteRequestUri($requestURI);
    }
}
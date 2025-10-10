<?php

class CtrlRoute extends Route {

    /**
     * @param string $route
     * @param Callable(object, string): HttpRequestContext $callUserFunc
     *  Argument for call_user_func containing instance [of controller] and method-name [defined inside controller].
     * @param array<string, string> $defaultArgs
     * @param string $nonceSecret
     */
    public function __construct(string $route, callable $callUserFunc, array $defaultArgs = [], $nonceSecret = '') {
        parent::__construct(
            $route,
            function () use ($callUserFunc) {
                return call_user_func($callUserFunc, new HttpRequestContext($this));
            },
            HttpMethod::Post,
            [new VerifyCsrfTokenOrNonceMiddleware($nonceSecret), new SanitizeRequestArgumentsMiddleware($defaultArgs)]);
    }

    protected function _matchesRequestUri(string $requestURI): bool {
        return parent::_matchesAbsoluteRequestUri($requestURI);
    }
}
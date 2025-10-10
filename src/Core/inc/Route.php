<?php

abstract class Route {

    var string $route;
    var HttpMethod $method;

    /** @var Closure(array<string, string>) : HttpResult */
    var \Closure $httpResultHandler;
    var array $args = [];

    /** @var InputMiddleware[] */
    var array $inputMiddleware = [];

    /** @var OutputMiddleware[] */
    var array $outputMiddleware = [];

    /**
     * @param \Closure(HttpRequestContext): HttpResult $handler
     * @param InputMiddleware[] $inputMiddleware
     * @param OutputMiddleware[] $outputMiddleware
     */
    public function __construct(string $route, \Closure $handler, HttpMethod $method = HttpMethod::Get,
        array $inputMiddleware = [], array $outputMiddleware = []) {
        $this->route = $route;
        $this->httpResultHandler = $handler;
        $this->method = $method;
        $this->inputMiddleware = $inputMiddleware;
        $this->outputMiddleware = $outputMiddleware;

    }

    public function getRequestArguments(): array {
        return $this->args;
    }

    /**
     * @param array<string, string> $args
     */
    public function setRequestArguments(array $args): void {
        $this->args = $args;
    }

    public function matchesRequestUri($requestURI): bool {
        if(!$requestURI) { return false; }
        if(!$this->matchesRequestMethod()) { return false; }
        return $this->_matchesRequestUri($requestURI);
    }

    public function matchesRequestMethod(): bool {
        return $this->method->matchesRequestUri();
    }

    protected function _matchesAbsoluteRequestUri($requestURI): bool {
        if($requestURI == $this->route) {
            return true;
        }

        if($requestURI == $this->route . '/') {
            return true;
        }

        return false;
    }

    protected function _matchesRegexRequestUri($requestURI): bool {
        if(preg_match($this->route, $requestURI, $this->args)) {
            $this->args = Sanitize::dictOfString($this->args);
            return true;
        }

        if($requestURI == $this->route . '/') {
            return true;
        }

        return false;
    }

    protected abstract function _matchesRequestUri(string $requestURI): bool;
}
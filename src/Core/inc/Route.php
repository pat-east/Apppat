<?php

abstract class Route {

    var string $route;
    var HttpMethod $method;

    /** @var Closure(array<string, string>) : HttpResult */
    var \Closure $httpResultHandler;
    var array $args = [];

    /**
     * @param \Closure(array<string, string>): HttpResult $handler
     */
    public function __construct(string $route, \Closure $handler, HttpMethod $method = HttpMethod::Get) {
        $this->route = $route;
        $this->httpResultHandler = $handler;
        $this->method = $method;
    }

    public function getRequestArguments(): array {
        return $this->args;
    }

    public function matchesRequestUri($requestURI): bool {
        if(!$requestURI) { return false; }
        if(!$this->matchesRequestMethod()) { return false; }
        return $this->_matchesRequestUri($requestURI);
    }

    public function matchesRequestMethod(): bool {
        return HttpMethod::MatchesRequestUri($this->method);
    }

    protected abstract function _matchesRequestUri(string $requestURI): bool;
}
<?php

class RegexViewRoute extends Route
{

    /**
     * @param class-string $viewClassName
     */
    public function __construct(string $route, string $viewClassName)
    {
        parent::__construct($route, function () use ($viewClassName) {
            return new ViewHttpResult($viewClassName);
        }, HttpMethod::Get);
    }

    public function _matchesRequestUri(string $requestURI): bool
    {
        return parent::_matchesRegexRequestUri($requestURI);
    }
}
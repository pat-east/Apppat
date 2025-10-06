<?php

class HttpRequestContext {

    public Route $route;

    public function __construct(Route $route) {
        $this->route = $route;
    }

    public function ParseArgs($args, $default) {
        return InputHelper::ParseArgs($args, $default);
    }
}
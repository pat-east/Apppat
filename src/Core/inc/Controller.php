<?php

abstract class Controller {

    var Core $core;

    public function __construct(Core $core) {
        $this->core = $core;
    }

    protected function registerRoute(Route $route): void {
        $this->core->router->registerRoute($route);
    }
}
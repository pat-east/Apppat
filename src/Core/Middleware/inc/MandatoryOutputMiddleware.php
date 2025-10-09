<?php

abstract class MandatoryOutputMiddleware extends Middleware {
    public abstract function run(Route $route, string $stdout): OutputMiddlewareResult;
}
<?php

class InputMiddlewareResult extends MiddlewareResult {}

abstract class InputMiddleware extends Middleware {
    public abstract function run(Route $route): InputMiddlewareResult;
}
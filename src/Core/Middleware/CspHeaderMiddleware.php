<?php

class CspHeaderMiddleware extends MandatoryOutputMiddleware {

    public function run(Route $route, string $stdout): OutputMiddlewareResult
    {
        header("Content-Security-Policy: default-src 'self'; script-src 'self'");

        return new OutputMiddlewareResult(middlewareResultStatus::Success, $stdout, $stdout);
    }
}
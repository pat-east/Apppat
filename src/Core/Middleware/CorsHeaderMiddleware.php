<?php

class CorsHeaderMiddleware extends MandatoryOutputMiddleware {

    public function run(Route $route, string $stdout): OutputMiddlewareResult {
        Cors::Headers();
        return new OutputMiddlewareResult(middlewareResultStatus::Success, $stdout, $stdout);
    }
}
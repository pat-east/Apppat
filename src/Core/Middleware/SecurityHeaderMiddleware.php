<?php

class SecurityHeaderMiddleware extends MandatoryOutputMiddleware {

    public function run(Route $route, string $stdout): OutputMiddlewareResult
    {
        header("X-Frame-Options: DENY");
        header("X-Content-Type-Options: nosniff");
        header("Referrer-Policy: strict-origin-when-cross-origin");
        header("Permissions-Policy: camera=(), microphone=(), geolocation=()");
        header("X-XSS-Protection: 1; mode=block");
        header("Cross-Origin-Embedder-Policy: require-corp");
        if (!Config::$Environment->isDevEnvironment()) {
            header("Strict-Transport-Security: max-age=63072000; includeSubDomains; preload");
            header("Cross-Origin-Opener-Policy: same-origin-allow-popups");
        }

        return new OutputMiddlewareResult(middlewareResultStatus::Success, $stdout, $stdout);
    }
}
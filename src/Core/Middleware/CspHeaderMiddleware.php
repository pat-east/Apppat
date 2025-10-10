<?php

class CspHeaderMiddleware extends MandatoryOutputMiddleware {


    public function run(Route $route, string $stdout): OutputMiddlewareResult {
        $cspDirectives = Csp::GetDirectives();
        $cspHeaderDirectives = '';
        foreach(array_keys($cspDirectives) as $src) {
            $cspHeaderDirectives .= $src . ' ' . implode(' ', $cspDirectives[$src]) . '; ';
        }

//        header("Content-Security-Policy: default-src 'self'; script-src 'self'");
        header('Content-Security-Policy: ' . $cspHeaderDirectives);

        return new OutputMiddlewareResult(middlewareResultStatus::Success, $stdout, $stdout);
    }
}
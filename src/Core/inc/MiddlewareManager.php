<?php

include_once('Middleware.php');

class MiddlewareManager {

    public function __construct() {

    }

    public function init() : void {
        Helper::IncludeOnce(Defaults::ABSPATH . '/Core/Middleware/inc');
        Helper::IncludeOnce(Defaults::ABSPATH . '/Core/Middleware');
    }

    public function run(Route $route) : void {

        // Let input middleware do their work
        if(!$this->runInputMiddleware($route)) {
            // If it fails, render 400-page
            new ViewHttpResult('Status400View')->run(new HttpRequestContext($route));
            return;
        }

        // If input middleware succeed, pass the request to the controller
        // Stdout gets redirected to a buffer*
        ob_start();
        $handler = $route->httpResultHandler;
        $httpResult = $handler($route->getRequestArguments());
        $httpResult->run(new HttpRequestContext($route));

        // Get stdout content from the buffer* ...
        $content = ob_get_clean();

        // let Middleware to their work ...
        $content = $this->runOutputMiddleware($route, $content);

        // ... and echo it
        echo $content;
    }

    private function runInputMiddleware(Route $route) : bool {
        foreach($route->inputMiddleware as $middleware) {
            $result = $middleware->run($route);
            if($result->result == MiddlewareResultStatus::Failure) {
                return false;
            }
        }
        return true;
    }

    private function runOutputMiddleware(Route $route, string $stdout) : string {

        foreach(Helper::GetDerivingClasses('MandatoryOutputMiddleware') as $middlewareClass) {
            $middleware = new $middlewareClass();
            $result = $middleware->run($route, $stdout);
            $stdout = $result->result == MiddlewareResultStatus::Success ? $result->processedStdout : $result->originalStdout;
        }

        foreach($route->outputMiddleware as $middleware) {
            $result = $middleware->run($route, $stdout);
            $stdout = $result->result == MiddlewareResultStatus::Success ? $result->processedStdout : $result->originalStdout;
        }
        return $stdout;
    }
}
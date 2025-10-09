<?php

class SanitizeRequestArgumentsMiddleware extends InputMiddleware
{

    /** @var array<string, string> */
    var array $defaultArgs;

    /**
     * @param array<string, string> $defaultArgs
     */
    public function __construct(array $defaultArgs)
    {
        $this->defaultArgs = $defaultArgs;
    }

    public function run(Route $route): InputMiddlewareResult
    {
        switch ($route->method) {
            case HttpMethod::Post:
                $route->setRequestArguments(InputHelper::ParseArgs($_POST, $this->defaultArgs));
                break;
            case HttpMethod::Get:
                $route->setRequestArguments(InputHelper::ParseArgs($_GET, $this->defaultArgs));
                break;
        }

        return new InputMiddlewareResult();
    }
}
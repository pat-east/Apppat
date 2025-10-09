<?php

class OutputMiddlewareResult extends MiddlewareResult
{

    var string $originalStdout;

    var string $processedStdout;

    public function __construct(
        MiddlewareResultStatus $result = MiddlewareResultStatus::Success,
        string                 $originalStdout = '', string $processedStdout = '')
    {
        parent::__construct($result);
        $this->originalStdout = $originalStdout;
        $this->processedStdout = $processedStdout;
    }
}

abstract class OutputMiddleware extends Middleware
{
    public abstract function run(Route $route, string $stdout): \OutputMiddlewareResult;
}
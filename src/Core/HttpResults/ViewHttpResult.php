<?php

class ViewHttpResult extends HttpResultContext {

    var string $viewClassName;
    var mixed $responseArgs;

    /**
     * @param class-string $viewClassName
     * @param mixed $args
     */
    public function __construct(string $viewClassName, array $responseArgs = []) {
        $this->viewClassName = $viewClassName;
        $this->responseArgs = $responseArgs;
    }

    public function run(HttpRequestContext $request): void {
        $view = new ($this->viewClassName)($request->route->getRequestArguments(), $this->responseArgs);
        Core::$Instance->themeManager->theme->render($view);
    }
}
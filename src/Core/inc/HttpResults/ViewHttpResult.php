<?php

class ViewHttpResult extends HttpResult {

    var string $viewClassName;

    /**
     * @param class-string $viewClassName
     */
    public function __construct(string $viewClassName) {
        $this->viewClassName = $viewClassName;
    }

    public function run(Route $route): void {
        $view = new ($this->viewClassName)($route->getRequestArguments());
        Core::$Instance->themeManager->theme->render($view);
    }
}
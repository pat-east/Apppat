<?php

class RedirectHttpResult extends HttpResultContext {

    var string $redirectUrl;

    /**
     * @param class-string $viewClassName
     */
    public function __construct(string $redirectUrl) {
        $this->redirectUrl = $redirectUrl;
    }

    public function run(HttpRequestContext $request): void {
        header('location: ' . $this->redirectUrl);
    }
}
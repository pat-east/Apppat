<?php

class ShortcodeMiddleware extends OutputMiddleware {

    public function run(Route $route, string $stdout = ''): OutputMiddlewareResult {

        $processed = $this->process($stdout);

        return new OutputMiddlewareResult(middlewareResultStatus::Success, $stdout, $processed);
    }

    private function process($stdout): string {
        return ShortcodeEngine::ProcessShortcodes($stdout);
    }
}
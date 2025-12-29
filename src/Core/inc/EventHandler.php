<?php

class EventHandler {
    var string $hookName;

    var \Closure $handler;

    var int $priority;

    public function __construct(string $hookName, callable $handler, int $priority = 100000) {
        $this->hookName = $hookName;
        $this->handler = $handler(...);
        $this->priority = $priority;
    }
}
<?php

class Event {
    var mixed $sender;
    var string $hookName;
    var string $description;

    var mixed $args;

    public function __construct(mixed $sender, string $hookName, array $args = [], $description = '') {
        $this->sender = $sender;
        $this->hookName = $hookName;
        $this->description = $description;
        $this->args = $args;
    }

    public function getSenderString(): string {
        return is_string($this->sender) ? $this->sender : get_class($this->sender);
    }
}
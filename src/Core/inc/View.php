<?php

abstract class View {

    var array $args = [];

    public function __construct(array $args) {
        $this->args = $args;
    }

    abstract public function render(): void;

}
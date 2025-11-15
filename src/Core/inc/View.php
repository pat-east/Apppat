<?php

abstract class View {

    var array $requestArgs = [];
    var array $responseArgs = [];

    public function __construct(array $requestArgs, array $responseArgs = []) {
        $this->requestArgs = $requestArgs;
        $this->responseArgs = $responseArgs;
    }

    abstract public function render(): void;

}
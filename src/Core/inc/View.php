<?php

abstract class View {

    var array $requestArgs = [];
    var array $responseArgs = [];

    public function __construct(array $requestArgs, array $responseArgs = []) {
        $this->requestArgs = $requestArgs;
        $this->responseArgs = $responseArgs;
    }

    /**
     * @return array<int, array{label:string,href:?string}>
     */
    public function getBreadcrumbItems(): array {
        return [];
    }

    abstract public function render(): void;

}

<?php

enum MiddlewareResultStatus {
    case Success;
    case Failure;
}

class MiddlewareResult {
    var MiddlewareResultStatus $result;

    public function __construct(MiddlewareResultStatus $result = MiddlewareResultStatus::Success) {
        $this->result = $result;
    }
}


abstract class Middleware {

}
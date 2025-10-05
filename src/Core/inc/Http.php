<?php

enum HttpMethod {
    case Get;
    case Post;
    case Put;
    case Delete;
    case Head;
    case Option;

    public function matchesRequestUri(): bool {
        return isset($_SERVER['REQUEST_METHOD']) && $this->toString() == $_SERVER['REQUEST_METHOD'];
    }

    public function toString(): string
    {
        return match($this) {
            HttpMethod::Get => 'GET',
            HttpMethod::Post => 'POST',
            HttpMethod::Put => 'PUT',
            HttpMethod::Delete => 'DELETE',
            HttpMethod::Head => 'HEAD',
            HttpMethod::Option => 'OPTION'
        };
    }
}

class Http {

}
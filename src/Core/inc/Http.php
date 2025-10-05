<?php

enum HttpMethod {
    case Get;
    case Post;
    case Put;
    case Delete;
    case Head;
    case Option;

    public static function MatchesRequestUri(HttpMethod $method): bool {

        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET': return $method == HttpMethod::Get;
            case 'POST': return $method == HttpMethod::Post;
            case 'PUT': return $method == HttpMethod::Put;
            case 'DELETE': return $method == HttpMethod::Delete;
            case 'HEAD': return $method == HttpMethod::Head;
            case 'OPTION': return $method == HttpMethod::Option;
            default: return false;
        }
    }
}

class Http {

}
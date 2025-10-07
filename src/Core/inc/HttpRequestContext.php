<?php

class HttpRequestContext {

    public Route $route;

    public function __construct(Route $route) {
        $this->route = $route;
    }

    /**
     * @param array<string, string> $args
     * @param array<string, string> $defaults
     * @param bool $sanitizeInputs
     * @return array<string, string>
     */
    public function parseArgs($args, $default): array
    {
        return InputHelper::ParseArgs($args, $default);
    }

    /**
     * @throws Exception
     */
    public function verifyCsrfToken() : bool {
        switch($this->route->method) {
            case HttpMethod::Get:
                return CsrfToken::VerifyToken($_GET[CsrfToken::CSRF_TOKEN_NAME]);
            case HttpMethod::Post:
                return CsrfToken::VerifyToken($_POST[CsrfToken::CSRF_TOKEN_NAME]);
            case HttpMethod::Put:
            case HttpMethod::Delete:
            case HttpMethod::Head:
            case HttpMethod::Option:
                throw new \Exception('To be implemented');
        }
        return false;
    }

    public function verifyNonce($nonceSecret) : bool {
        switch($this->route->method) {
            case HttpMethod::Get:
                return Nonce::Verify($nonceSecret, $_GET[Nonce::NONCE_PAYLOAD], $_GET[Nonce::NONCE_NAME]);
            case HttpMethod::Post:
                return Nonce::Verify($nonceSecret, $_POST[Nonce::NONCE_PAYLOAD], $_POST[Nonce::NONCE_NAME]);
            case HttpMethod::Put:
            case HttpMethod::Delete:
            case HttpMethod::Head:
            case HttpMethod::Option:
                throw new \Exception('To be implemented');
        }
        return false;
    }
}
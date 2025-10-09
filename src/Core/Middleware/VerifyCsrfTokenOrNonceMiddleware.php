<?php

class VerifyCsrfTokenOrNonceMiddleware extends InputMiddleware
{

    private string $nonceSecret;

    public function __construct(string $nonceSecrent = '')
    {
        $this->nonceSecret = $nonceSecrent;
    }

    public function run(Route $route): InputMiddlewareResult
    {

        if ($route->method == HttpMethod::Post) {

            if (!isset($_POST[CsrfToken::CSRF_TOKEN_NAME]) && !isset($_POST[Nonce::NONCE_NAME])) {
                Logger::Info(__FILE__, 'There is no csrf-token or nonce.');
                return new InputMiddlewareResult(MiddlewareResultStatus::Failure);
            }

            if (isset($_POST[CsrfToken::CSRF_TOKEN_NAME])) {
                if (!CsrfToken::Verify(Sanitize::Text($_POST[CsrfToken::CSRF_TOKEN_NAME]))) {
                    Logger::Info(__FILE__, 'Could not verify csrf-token.');
                    return new InputMiddlewareResult(MiddlewareResultStatus::Failure);
                }
            }

            if (isset($_POST[Nonce::NONCE_NAME])) {
                if (!Nonce::Verify(
                    $this->nonceSecret,
                    Sanitize::Text($_POST[Nonce::NONCE_PAYLOAD]),
                    Sanitize::Text($_POST[Nonce::NONCE_NAME]))) {
                    Logger::Info(__FILE__, 'Could not verify nonce.');
                    return new InputMiddlewareResult(MiddlewareResultStatus::Failure);
                }
            }
        }

        return new InputMiddlewareResult();
    }
}
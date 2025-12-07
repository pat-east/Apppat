<?php

class ErrorHandler {

    public function __construct() {

    }

    public function init(): void {
        set_error_handler([$this, 'handleError'], E_ALL & ~E_NOTICE & ~E_USER_NOTICE);
    }

    public function handleException(Throwable $exception): never {
        /** getCode() might also return non-int values. This cast fixes this issue. */
        $this->handleError((int)$exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
    }

    private function errorHandlerCallback(int $errno, string $errstr, string $errfile, int $errline): never {
        $this->handleError($errno, $errstr, $errfile, $errline);
    }

    public function handleError(int $errno, string $errstr, string $errfile, int $errline): never {
        Log::Error(__FILE__, sprintf('%s [%s:%s]', $errstr, $errfile, $errline));
        http_response_code(500);
        include_once(Defaults::ABSPATH . '/Views/500.php');
        exit;
    }

}
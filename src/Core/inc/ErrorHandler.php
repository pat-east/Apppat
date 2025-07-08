<?php

class ErrorHandler {

    public function __construct() {

    }

    public function init(): void {
        set_error_handler([$this, 'handleError'], E_ALL & ~E_NOTICE & ~E_USER_NOTICE);
    }

    public function handleException(Throwable $exception): never {
        $this->handleError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
    }

    private function errorHandlerCallback(int $errno, string $errstr, string $errfile, int $errline): never {
        $this->handleError($errno, $errstr, $errfile, $errline);
    }

    private function handleError(int $errno, string $errstr, string $errfile, int $errline): never {
        Log::Error(__FILE__, $errstr);
        http_response_code(500);
        include_once(Defaults::ABSPATH . '/views/500.php');
        exit;
    }

}
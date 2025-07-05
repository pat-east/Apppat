<?php

use JetBrains\PhpStorm\NoReturn;

class ErrorHandler {

    function __construct() {

    }

    public function init(): void {
        set_error_handler([$this, 'handleError'], E_ALL & ~E_NOTICE & ~E_USER_NOTICE);
    }

    #[NoReturn] public function handleException(Throwable $exception): void {
        $this->handleError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
    }

    #[NoReturn] private function errorHandlerCallback(int $errno, string $errstr, string $errfile, int $errline): void {
        $this->handleError($errno, $errstr, $errfile, $errline);
    }

    #[NoReturn] private function handleError(int $errno, string $errstr, string $errfile, int $errline): void {
        Log::Error(__FILE__, $errstr);
        http_response_code(500);
        include_once(Defaults::ABSPATH . '/views/500.php');
        exit;
    }

}
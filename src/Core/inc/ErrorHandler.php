<?php

//register_shutdown_function('__fatal_error_handler');

function __fatal_error_handler() {


    $error = error_get_last();

    if($error !== NULL) {
        $errno   = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr  = $error["message"];

        ?>
        <hr>
        <p><?= $errfile ?>:<?= $errline ?></p>
        <pre style="white-space: pre-wrap; "><?= $errstr ?></pre>


        <?php

    }
}

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
        if(PHP_SAPI === 'cli') {
            fwrite(STDERR, sprintf("ERROR: %s [%s:%s]\n", $errstr, $errfile, $errline));
            exit(1);
        }
        http_response_code(500);
        include_once(Defaults::ABSPATH . '/Views/500.php');
        exit;
    }

}

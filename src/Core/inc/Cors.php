<?php

class Cors {

    /** @var string[] List of allowed origins.
     *  TODO: Move origins in some kind of CorsConfig.
     */
    static array $AllowedOrigins = [ 'https://www.some-domain.local' ];

    public static function Headers() : void {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        if($origin) {
            if(in_array($origin, self::$AllowedOrigins)) {
                header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Max-Age: 86400');    // cache for 1 day
            }
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                // May also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            }

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                // May also be header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
                header("Access-Control-Allow-Headers: 'Content-Type, Authorization'");
            }

        }
    }
}
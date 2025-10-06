<?php

class Session {

    public static ?Session $Instance = null;

    public function __construct() {
        if(self::$Instance != null) {
            return;
        }

        self::$Instance = $this;
    }

    function init(): void {

        // Set recommended cookie options
        ini_set('session.name', Config::$Session->name);
        ini_set('session.cookie_path', Config::$Session->cookie_path);
        ini_set('session.cookie_lifetime', Config::$Session->cookie_lifetime);
        ini_set('session.cookie_domain', Config::$Session->cookie_domain);
        if(!str_ends_with($_SERVER['HTTP_HOST'], '.local')) {
            // Is it also allowed to use http in dev-environment
            ini_set('session.cookie_secure', Config::$Session->cookie_secure);
        }
        ini_set('session.cookie_httponly', Config::$Session->cookie_httponly);
        ini_set('session.cookie_samesite', Config::$Session->cookie_samesite);


        session_start();
    }
}
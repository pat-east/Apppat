<?php

class Session {

    public static ?Session $Instance = null;

    public function __construct() {
        if(self::$Instance != null) {
            return;
        }

        self::$Instance = $this;
    }

    public function setUserUid($userUid): void {
        $_SESSION['user_uid'] = $userUid;
    }

    public function getUserUid(): string|null {
        return $_SESSION['user_uid'] ?? null;
    }

    public function init(): void {

        // Set recommended cookie options
        ini_set('session.name', Config::$Session->name);
        ini_set('session.cookie_path', Config::$Session->cookie_path);
        ini_set('session.cookie_lifetime', Config::$Session->cookie_lifetime);
        ini_set('session.cookie_domain', Config::$Session->cookie_domain);
        if(!Config::$Environment->isDevEnvironment()) {
            // Is it also allowed to use http in dev-environment
            ini_set('session.cookie_secure', Config::$Session->cookie_secure);
        }
        ini_set('session.cookie_httponly', Config::$Session->cookie_httponly);
        ini_set('session.cookie_samesite', Config::$Session->cookie_samesite);
        ini_set('session.sid_length', Config::$Session->sid_length);
        ini_set('session.use_only_cookies', Config::$Session->use_only_cookies);
        ini_set('session.use_strict_mode', Config::$Session->use_strict_mode);


        session_start();
    }
}
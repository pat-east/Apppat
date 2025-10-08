<?php

class CsrfToken {

    public const string CSRF_TOKEN_NAME = 'csrf-token';

    public static function Create() : CsrfToken {
        return new CsrfToken();
    }

    public static function Verify(string $token) : bool {
        if(!isset($_SESSION[self::CSRF_TOKEN_NAME])) {
            return false;
        }
        return in_array($token, $_SESSION[self::CSRF_TOKEN_NAME]);
    }

    public string $token {
        get {
            return $this->token;
        }
    }

    private function __construct() {
        $uuid = Helper::CreateUuidV4();
        $this->token = md5($uuid);

        if(!isset($_SESSION[self::CSRF_TOKEN_NAME])) {
            $_SESSION[self::CSRF_TOKEN_NAME] = [];
        }
        $_SESSION[self::CSRF_TOKEN_NAME][] = $this->token;
    }

}
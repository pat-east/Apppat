<?php

class Csp {

    static ?string $NonceForThisRequest = null;

    public static function CreateNonce($keepNonceForThisRequest = true): string {

        if ($keepNonceForThisRequest && self::$NonceForThisRequest !== null) {
            return self::$NonceForThisRequest;
        }

        $nonce = md5(uniqid(rand(), true));
        return $keepNonceForThisRequest ?
            self::$NonceForThisRequest = $nonce
            : $nonce;
    }

    public static function GetDirectives(): array {
        // TODO load directives from config

        $nonce = self::CreateNonce();

        $self = sprintf('https://%s', $_SERVER['HTTP_HOST']);
        if (Config::$Environment->isDevEnvironment()) {
            $self .= ' ';
            $self .= sprintf('http://%s', $_SERVER['HTTP_HOST']);
        }

        return [
            'default-src' => [$self],
            'img-src' => ["'self'", 'data:'],
            'style-src' => ["'self'"],
            'script-src' => ["'nonce-$nonce'", "'strict-dynamic'"],
            'frame-src' => ["'none'"],
            'object-src' => ["'self'"],
            'connect-src' => ["'self'"]
        ];
    }
}
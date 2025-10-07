<?php

class Nonce {

    public const string NONCE_NAME = 'NONCE';
    public const string NONCE_PAYLOAD = 'NONCE_PAYLOAD';

    public static function Create(string $secret, ?string $payload = null) : Nonce {
        if($payload === null) {
            $payload = random_int(111111111, 999999999);
        }
        return new Nonce(self::GenerateNonce($secret, $payload), $payload);
    }

    public static function Verify(string $secret, string $payload, ?string $token) : bool {
        return $token === self::GenerateNonce($secret, $payload);
    }

    private static function GenerateNonce(string $secret, string $payload) : string {
        return md5(Config::$Environment->noncePayload . $secret . $payload);
    }

    public string $nonce;
    public string $payload;

    private function __construct(string $nonce, string $payload) {
        $this->nonce = $nonce;
        $this->payload = $payload;
    }
}
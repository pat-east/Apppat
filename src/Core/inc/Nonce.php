<?php

class Nonce {

    public const string NONCE_NAME = 'nonce';
    public const string NONCE_PAYLOAD = 'nonce_payload';

    public static function Create(string $secret, ?string $payload = null) : Nonce {
        if($payload === null) {
            $payload = random_int(111111111, 999999999);
        }
        return new Nonce(self::GenerateNonce($secret, $payload), $payload);
    }

    public static function Verify(string $secret, string $payload, string $token) : bool {
//        Log::Debug(__FILE__, 'Verify nonce [secret=%s; payload=%s; token=%s]', $secret, $payload, $token);
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
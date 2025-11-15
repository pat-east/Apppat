<?php

use Random\RandomException;

class Crypto {

    public const string UppercaseCharactersKeyspace = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public const string LowercaseCharactersKeyspace = 'abcdefghijklmnopqrstuvwxyz';
    public const string NumbersKeyspace = '0123456789';
    public const string SpecialCharacterKeyspace = '!@#$%^&*()-_=+?.,';

    /**
     * @throws RandomException
     */
    public static function UuidV4(): string {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * @throws RandomException
     */
    public static function CreateRandomString(int $length, string $keyspace): string {

        $str = '';
        $max = strlen($keyspace) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $keyspace[random_int(0, $max)];
        }

        return $str;
    }
}
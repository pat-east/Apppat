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

    /**
     * @throws Exception
     * @return array<string, string> Returns an array containing public and private key.
     */
    public static function CreateRsaKeyPair($keylength = CrytpoConfig::DefaultKeyLength): array {
        $config = [
            "private_key_bits" => $keylength,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];
        $pkey = openssl_pkey_new($config);
        if($pkey === false) {
            throw new Exception(sprintf('Failed to create key pair [openssl_error_string=%s]', openssl_error_string()));
        }

        $privateKey = '';
        openssl_pkey_export($pkey, $privateKey);

        $details  = openssl_pkey_get_details($pkey);
        $publicKey = $details['key'];

        return [
            'private_key' => $privateKey,
            'public_key' => $publicKey,
        ];
    }

    public static function EncryptRsa(string $data, string $pubk): string {
        $pubKeyId = openssl_pkey_get_public($pubk);
        if ($pubKeyId === false) {
            throw new Exception(sprintf('Failed to load key [openssl_error_string=%s]', openssl_error_string()));
        }

        $encrypted = '';
        $result = openssl_public_encrypt(
            $data,
            $encrypted,
            $pubKeyId,
            OPENSSL_PKCS1_OAEP_PADDING
        );

        if (!$result) {
            throw new Exception(sprintf('Failed to encrypt data [openssl_error_string=%s]', openssl_error_string()));
        }

        return base64_encode($encrypted);
    }

    public static function DecryptRsa(string $cipher, string $pk): string {
        $encrypted = base64_decode($cipher);

        $privKeyId = openssl_pkey_get_private($pk);
        if ($privKeyId === false) {
            throw new Exception(sprintf('Failed to load key [openssl_error_string=%s]', openssl_error_string()));
        }

        $decrypted = '';
        $result = openssl_private_decrypt(
            $encrypted,
            $decrypted,
            $privKeyId,
            OPENSSL_PKCS1_OAEP_PADDING
        );

        if (!$result) {
            throw new Exception(sprintf('Failed to decrypt data [openssl_error_string=%s]', openssl_error_string()));
        }

        return $decrypted;
    }
}
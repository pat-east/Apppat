<?php

class UserEncryption {

    var UserModel $user;
    var string $userRsaPrivateKeyPath;
    var string $userRsaPublicKeyPath;

    public function __construct(UserModel $user) {
        $this->user = $user;
        $this->userRsaPrivateKeyPath = sprintf('%s/%s.pk',
            rtrim(Config::$Crypto->getUserRsaKeyPairBaseDir(), DIRECTORY_SEPARATOR),
            $user->uid);
        $this->userRsaPublicKeyPath = sprintf('%s/%s.pubk',
            rtrim(Config::$Crypto->getUserRsaKeyPairBaseDir(), DIRECTORY_SEPARATOR),
            $user->uid);

        $this->init();
    }

    /**
     * @throws Exception
     */
    public function encrypt(string $data): string {
        $key = file_get_contents($this->userRsaPublicKeyPath);
        return Crypto::EncryptRsa($data, $key);
    }

    /**
     * @throws Exception
     */
    public function createAndStoreKeyPair(): void {
        $keyPair = Crypto::CreateRsaKeyPair();
        file_put_contents($this->userRsaPrivateKeyPath, $keyPair['private_key']);
        file_put_contents($this->userRsaPublicKeyPath, $keyPair['public_key']);
        chmod($this->userRsaPrivateKeyPath, 0600);
        chmod($this->userRsaPublicKeyPath, 0600);
    }

    /**
     * @throws Exception
     */
    public function decrypt(string $data): string {
        $key = file_get_contents($this->userRsaPrivateKeyPath);
        return Crypto::DecryptRsa($data, $key);
    }

    private function init(): void {
        if(!$this->keyPairExists()) {
            try {
                $this->createAndStoreKeyPair();
            } catch (Exception $e) {
                Log::Error(__FILE__, 'Could not RSA keypair for user [ex=%s]', $e->getMessage());
            }
        }
    }

    private function keyPairExists(): bool {
        return file_exists($this->userRsaPrivateKeyPath) && file_exists($this->userRsaPublicKeyPath);
    }
}
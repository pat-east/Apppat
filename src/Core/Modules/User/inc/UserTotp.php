<?php

class UserTotp {

    var UserModel $user;

    public function __construct(UserModel $user) {
        $this->user = $user;
        $this->init();
    }

    public function mfaTotpEnabled(): bool {
        return $this->user->totpEnabled;
    }

    public function getTotpSecret(): string {
        $encSecret = $this->user->getTotpSecret();
        return new UserEncryption($this->user)->decrypt($encSecret);
    }

    public function getRecoveryKey(): string {
        $encSecret = $this->user->getTotpRecoverKey();
        return new UserEncryption($this->user)->decrypt($encSecret);
    }

    public function getCurrentTotp(): string {
        $secret = $this->getTotpSecret();
        return new Totp()->getCurrentTotp($secret);
    }

    public function getProvisioningUri(): string {
        $secret = $this->getTotpSecret();
        return new Totp()->getProvisioningUri($secret, $this->user->username);
    }

    public function verifyTotp(string $totpCode): bool {
        $secret = $this->getTotpSecret();
        return new Totp()->verify($secret, $totpCode);
    }

    public function verifyRecoveryKey(string $recoveryKey): bool {
        $secret = $this->getRecoveryKey();
        return $secret === preg_replace('/\s+/', '', $recoveryKey);
    }

    public function enableTotp(): void {
        $this->user->update('totp_enabled', Defaults::YES);
    }

    public function disableTotp(bool $resetSecrets): void {
        $this->user->update('totp_enabled', Defaults::NOH);
        if($resetSecrets) {
            $this->user->setTotpSecret(null);
            $this->user->setTotpRecoverKey(null);
        }
    }

    private function init(): void {
        $totp = new Totp();
        $userEncryption = new UserEncryption($this->user);
        if(!$this->user->getTotpSecret()) {
            $secret = $totp->generateSecret();
            $encSecret = $userEncryption->encrypt($secret);
            $this->user->setTotpSecret($encSecret);
        }
        if(!$this->user->getTotpRecoverKey()) {
            $secret = $totp->generateRecoveryKey();
            $encSecret = $userEncryption->encrypt($secret);
            $this->user->setTotpRecoverKey($encSecret);
        }
    }
}
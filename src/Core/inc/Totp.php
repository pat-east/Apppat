<?php

class Totp {

    const RECOVERY_KEY_LENGTH = 24;

    public function __construct() {

    }

    public function generateSecret(): string {
        $clock = new ApppatTotpClock();
        $totp = OTPHP\TOTP::generate($clock);
        return $totp->getSecret();
    }

    public function generateRecoveryKey(): string {
        return Crypto::CreateRandomString(self::RECOVERY_KEY_LENGTH, Crypto::NumbersKeyspace);
    }

    public function getCurrentTotp(string $secret) {
        $clock = new ApppatTotpClock();
        $totp = OTPHP\TOTP::createFromSecret($secret, $clock);
        return $totp->now();
    }

    public function getProvisioningUri(string $secret, string $ussuer) {
        $clock = new ApppatTotpClock();
        $totp = OTPHP\TOTP::createFromSecret($secret, $clock);
        $totp->setLabel(Defaults::APPTITLE);
        $totp->setIssuer($ussuer);
        return $totp->getProvisioningUri();
    }

    public function verify(string $secret, string $totp): string {
        return $this->getCurrentTotp($secret) === $totp;
    }
}

/**
 * @internal
 */
final class ApppatTotpClock implements Psr\Clock\ClockInterface
{
    private ?DateTimeImmutable $dateTime = null;

    public function now(): DateTimeImmutable
    {
        return $this->dateTime ?? new DateTimeImmutable();
    }

    public function setDateTime(?DateTimeImmutable $dateTime): void
    {
        $this->dateTime = $dateTime;
    }
}
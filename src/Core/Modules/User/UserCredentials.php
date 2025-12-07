<?php

use Random\RandomException;

class UserCredentials {

    const int MIN_PASSWORD_LENGTH = 16;
    const int MIN_USERNAME_LENGTH = 3;

    /**
     * @throws RandomException
     */
    public static function CreateRandomPassword($length = self::MIN_PASSWORD_LENGTH): string {
        $numChars = 4;
        $sc = Crypto::CreateRandomString($numChars, Crypto::SpecialCharacterKeyspace);
        $nc = Crypto::CreateRandomString($numChars, Crypto::NumbersKeyspace);
        $uc = Crypto::CreateRandomString($numChars, Crypto::UppercaseCharactersKeyspace);
        $lc = Crypto::CreateRandomString($length - $numChars * 3, Crypto::LowercaseCharactersKeyspace);

        $pwd = $sc . $nc . $uc . $lc;

        return str_shuffle($pwd);
    }

    public static function VerifyPasswordCriteria($password): bool {

        if(strlen($password) < self::MIN_PASSWORD_LENGTH) {
            return false;
        }

        if(!strpbrk(Crypto::SpecialCharacterKeyspace, $password)) {
            return false;
        }

        if(!strpbrk(Crypto::NumbersKeyspace, $password)) {
            return false;
        }

        if(!strpbrk(Crypto::UppercaseCharactersKeyspace, $password)) {
            return false;
        }

        if(!strpbrk(Crypto::LowercaseCharactersKeyspace, $password)) {
            return false;
        }

        return true;
    }

    public static function VerifyUsername($username): bool {
        if(preg_match('/^[A-Za-z0-9]+$/', $username) !== 1) {
            return false;
        }
        return strlen($username) >= UserCredentials::MIN_USERNAME_LENGTH;
    }

    public static function VerifyEmail($email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    var UserModel $user;
    var UserPasswordRecoveryModel $userPasswordRecoveryModel;

    var UserTotp $totp;

    public function __construct(UserModel $user) {
        $this->user = $user;
        $this->userPasswordRecoveryModel = new UserPasswordRecoveryModel();
        $this->totp = new UserTotp($user);

    }

    public function sendRecoverPasswordMail(): bool {

        if($this->user->email) {

            $recovery = $this->userPasswordRecoveryModel->createRecovery(
                $this->user->uid, $this->user->username, $this->user->email);
            $recoveryLink = sprintf('%s/password-recovery/%s', Defaults::BASEURL, $recovery->recoveryToken);

            $body = '
<p>Follow the link to a new password:</p>
<a href="{recover-password-link}">Set new password</a>
<p><small>This link is available until ...</small></p>
<p><small>Please ignore this mail, if you have not initiated password recovery.</small></p>';

            $body = str_replace('{recover-password-link}', $recoveryLink, $body);

            $mail = new Mail();
            $mail->addReceiver($this->user->email);
            $mail->addReceiver($this->user->email, $this->user->username);
            $mail->setHeadline('Password recovery');
            $mail->setSubline('Set a new password');
            $mail->setSubject('Recover your password');
            $mail->setBody($body);
            $mail->send();
            return true;
        }
        return false;
    }

}
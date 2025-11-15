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


}
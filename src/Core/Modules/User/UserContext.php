<?php

class UserContext {

    private static ?UserContext $Instance = null;

    public static function Instance(): UserContext {
        if(self::$Instance) {
            return self::$Instance;
        }
        return self::$Instance = new UserContext();
    }

    public bool $isUserLoggedIn = false;

    public Session $session;

    public UserModel $user;
    public UserEncryption $userEncryption;

    private function __construct() {
        if(self::$Instance != null) {
            return;
        }

        $this->session = Session::$Instance;
        self::$Instance = $this;

        $this->init();
    }

    public function authenticate(string $usernameOrEmail, string $password): bool {
        $user = null;

        if(!UserCredentials::VerifyPasswordCriteria($password)) {
            return false;
        }

        if(UserCredentials::VerifyEmail($usernameOrEmail)) {
            $user = UserModel::GetByEmail($usernameOrEmail);
        }

        if ($user == null && UserCredentials::VerifyUsername($usernameOrEmail)) {
            $user = UserModel::GetByUsername($usernameOrEmail);
        }

        if($user) {
            $userPwdHash = $user->getPasswordHash();
            if(password_verify($password, $userPwdHash)) {
                $this->session->setUserUid($user->uid);
                $this->user = $user;
                $this->isUserLoggedIn = true;
                return true;
            }

        }

        return false;
    }

    private function init() {
        if($userUid = $this->session->getUserUid()) {
            $this->user = UserModel::GetByUid($userUid);
            $this->userEncryption = new UserEncryption($this->user);
            $this->isUserLoggedIn = true;
        } else {
            $this->user = new UserModel();
        }
    }
}
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

    public UserCredentials $userCredentials;

    public UserRoles $userRoles;

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

    /**
     * @param class-string[] $privilege One or more strings of class-names of UserPrivilege classes.
     * @return bool Return true, if current user has privileges.
     */
    public function hasPrivilege(... $privileges): bool {
        if(!$this->isUserLoggedIn) { return false; }
        if(count($privileges) === 0) { return false; }

        $userPrivs = $this->userRoles->getPrivilegesUids();
        foreach($privileges as $priv) {
            if(!in_array($priv, $userPrivs)) {
                return false;
            }
        }

        return true;
    }

    private function init() {
        $this->user = new UserModel();

        if($userUid = $this->session->getUserUid()) {
            if($user = UserModel::GetByUid($userUid)) {
                $this->user = $user;
                $this->userEncryption = new UserEncryption($this->user);
                $this->userCredentials = new UserCredentials($this->user);
                $this->userRoles = new UserRoles($this->user);
                $this->isUserLoggedIn = true;
            }

        }
    }
}
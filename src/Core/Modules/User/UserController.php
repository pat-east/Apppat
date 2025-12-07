<?php

class UserController extends Controller {

    public const string NONCE_SECRET = 'user-nonce-secret-o09hF1BIfK';

    public function __construct(Core $core) {
        parent::__construct($core);

        $this->registerRoute(new ViewRoute('/login', 'LoginView'));
        $this->registerRoute(new CtrlRoute('/logout', [ $this, 'logoutUser' ], [], '', HttpMethod::Get));

        $this->registerRoute(new ViewRoute('/recover-password', 'RecoverPasswordView'));
        $this->registerRoute(new CtrlRoute(
            '/recover-password',
            [ $this, 'recoverPassword' ],
            [ 'username' => '' ],
            self::NONCE_SECRET));

        $this->registerRoute(new RegexViewRoute('#^/password-recovery/(?P<recovery_token>[^/]+)/?$#', 'PasswordRecoveryView'));
        $this->registerRoute(new CtrlRoute(
            '/password-recovery',
            [ $this, 'passwordRecovery' ],
            [ 'recovery_token' => '', 'password' => '', 'password-repeat' => '' ],
            self::NONCE_SECRET));

        $this->registerRoute(new ViewRoute('/user/register', 'UserRegisterView'));
        $this->registerRoute(new CtrlRoute('/user/register', [$this, 'registerUser'], [
            'username' => '',
            'email' => '',
            'password' => '',
            'password-repeat' => '',
        ], self::NONCE_SECRET));

        $this->registerRoute(new CtrlRoute('/login', [$this, 'loginUser'], [
            'username' => '',
            'password' => '',
        ], self::NONCE_SECRET));

        $this->registerRoute(new CtrlRoute('/user/password', [$this, 'changeUserPassword'], [
            'current-password' => '',
            'new-password' => '',
            'new-password-repeat' => '',
        ], self::NONCE_SECRET));

        $this->registerRoute(new RegexViewRoute('#^/user/(?P<username>[^/]+)/?$#', 'UserView'));
    }

    public function loginUser(HttpRequestContext $request): HttpResultContext {
        $args = $request->route->getRequestArguments();
        $username = $args['username'];
        $password = $args['password'];
        $totp = $args['totp-code'];
        $user = UserModel::GetByUsernameOrEmail($username);
        $userCredentials = new UserCredentials($user);
        if($userCredentials->totp->mfaTotpEnabled()) {
            $userTotp = new UserTotp($user);
            if(!$userTotp->verifyTotp($totp)) {
                return new ViewHttpResult('LoginView',
                    [
                        'status' => LoginView::StatusInvalidLogin
                    ]);
            }
        }

        if(!UserContext::Instance()->authenticate($username, $password)) {
            return new ViewHttpResult('LoginView',
                [
                    'status' => LoginView::StatusInvalidLogin
                ]);
        }

        return new RedirectHttpResult('/dashboard');

    }

    public function logoutUser(HttpRequestContext $request): HttpResultContext {
        session_destroy();
        return new RedirectHttpResult('/');
    }

    public function registerUser(HttpRequestContext $request) : HttpResultContext {
        $args = $request->route->getRequestArguments();
        if(!UserCredentials::VerifyEmail($args['email'])) {
            return new ViewHttpResult('UserRegisterView',
                [
                    'status' => UserRegisterView::StatusInvalidEmail
                ]);
        }
        if(!UserCredentials::VerifyUsername($args['username'])) {
            return new ViewHttpResult('UserRegisterView',
                [
                    'status' => UserRegisterView::StatusInvalidUsername
                ]);
        }
        if(!UserCredentials::VerifyPasswordCriteria($args['password'])) {
            return new ViewHttpResult('UserRegisterView',
                [
                    'status' => UserRegisterView::StatusInvalidPassword
                ]);
        }
        if($args['password'] !== $args['password-repeat']) {
            return new ViewHttpResult('UserRegisterView',
                [
                    'status' => UserRegisterView::StatusInvalidPasswordRepeat
                ]);
        }
        if(UserModel::UserExists($args['username'], $args['email'])) {
            return new ViewHttpResult('UserRegisterView',
                [
                    'status' => UserRegisterView::StatusUserAlreadyExists
                ]);
        }

        $user = UserModel::Create($args['username'], $args['email'], $args['password']);

        if($user) {
            new UserEncryption($user)->createAndStoreKeyPair();
            return new ViewHttpResult('UserWelcomeView',
                [
                    'user' => $user
                ]);
        }

        return new ViewHttpResult('UserRegisterView',
            [
                'status' => UserRegisterView::StatusError
            ]);
    }

    public function changeUserPassword(HttpRequestContext $request) : HttpResultContext {
        // TODO
        return new ViewHttpResult('ChangeUserPasswordView',
            [
                'status' => ChangeUserPasswordView::StatusInvalidPassword
            ]);
    }

    public function recoverPassword(HttpRequestContext $request) : HttpResultContext {
        $args = $request->route->getRequestArguments();
        $user = UserModel::GetByUsernameOrEmail($args['username']);
        if($user !== null) {
            $userCredentials = new UserCredentials($user);
            $userCredentials->sendRecoverPasswordMail();
        }
        if(Config::$AppConfig->PasswordRecoveryFalsePositive) {
            return new ViewHttpResult('RecoverPasswordView',
                [
                    'status' => RecoverPasswordView::StatusRecoveryMailSent
                ]);
        }
        return new ViewHttpResult('RecoverPasswordView',
            [
                'status' => RecoverPasswordView::StatusRecoveryFailed
            ]);
    }

    public function passwordRecovery(HttpRequestContext $request) : HttpResultContext {
        $args = $request->route->getRequestArguments();
        $recoveryToken = $args['recovery_token'];
        $pwd = $args['password'];
        $pwdRepeat = $args['password-repeat'];
        if(!UserCredentials::VerifyPasswordCriteria($pwd)) {
            return new ViewHttpResult('PasswordRecoveryView',
                [
                    'status' => PasswordRecoveryView::StatusInvalidPassword
                ]);
        }
        if($pwd !== $pwdRepeat) {
            return new ViewHttpResult('PasswordRecoveryView',
                [
                    'status' => PasswordRecoveryView::StatusInvalidPasswordRepeat
                ]);
        }

        $recovery = UserPasswordRecoveryModel::GetByRecoveryToken($recoveryToken);
        if($recovery === null && Config::$AppConfig->PasswordRecoveryFalsePositive) {
            return new ViewHttpResult('PasswordRecoveryView',
                [
                    'status' => PasswordRecoveryView::StatusPasswordChanged
                ]);
        }
        if($recovery === null) {
            return new ViewHttpResult('PasswordRecoveryView',
                [
                    'status' => PasswordRecoveryView::StatusInvalidRecoveryToken
                ]);
        }

        UserModel::GetByUid($recovery->userUid)->updatePassword($pwd);

        return new ViewHttpResult('PasswordRecoveryView',
            [
                'status' => PasswordRecoveryView::StatusPasswordChanged
            ]);
    }
}
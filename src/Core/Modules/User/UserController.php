<?php

class UserController extends Controller {

    public const string NONCE_SECRET = 'user-nonce-secret-o09hF1BIfK';

    public function __construct(Core $core) {
        parent::__construct($core);

        $this->registerRoute(new ViewRoute('/login', 'LoginView'));
        $this->registerRoute(new CtrlRoute('/logout', [ $this, 'logoutUser' ], [], '', HttpMethod::Get));
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

    public function loginUser(HttpRequestContext $request): HttpResult {
        $args = $request->route->getRequestArguments();
        if(!UserContext::Instance()->authenticate($args['username'], $args['password'])) {
            return new ViewHttpResult('LoginView',
                [
                    'status' => LoginView::StatusInvalidLogin
                ]);
        }

        return new RedirectHttpResult('/dashboard');

    }

    public function logoutUser(HttpRequestContext $request): HttpResult {
        session_destroy();
        return new RedirectHttpResult('/');
    }

    public function registerUser(HttpRequestContext $request) : HttpResult {
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

    public function changeUserPassword(HttpRequestContext $request) : HttpResult {
        // TODO
        return new ViewHttpResult('ChangeUserPasswordView',
            [
                'status' => ChangeUserPasswordView::StatusInvalidPassword
            ]);
    }
}
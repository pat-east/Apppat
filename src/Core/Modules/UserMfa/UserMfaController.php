<?php

class UserMfaController extends Controller {

    public const string NONCE_SECRET = 'user-mfa-nonce-secret-BjC9OXttBX';

    public function __construct(Core $core) {
        parent::__construct($core);

        $this->registerRoute(new ViewRoute('/recover-mfa-totp', 'RecoverUserMfaTotpView'));
        $this->registerRoute(new CtrlRoute(
            '/recover-mfa-totp',
            [ $this, 'recoverMfaTotp' ],
            [
                'username' => '',
                'recovery-key' => '',
            ],
            self::NONCE_SECRET));
        $this->registerRoute(new Viewroute('/dashboard/user/settings/mfa/totp/enable/', 'EnableUserMfaTotpView'));
        $this->registerRoute(new Viewroute('/dashboard/user/settings/mfa/totp/disable/', 'DisableUserMfaTotpView'));
        $this->registerRoute(new CtrlRoute(
            '/dashboard/user/settings/mfa/totp/enable/',
            [ $this, 'enableMfaTotp' ],
            [
                'totp-code' => ''
            ], self::NONCE_SECRET));
        $this->registerRoute(new CtrlRoute(
            '/dashboard/user/settings/mfa/totp/disable/',
            [ $this, 'disableMfaTotp' ],
            [
                'totp-code' => '',
                'renew-secrets' => false
            ], self::NONCE_SECRET));
    }

    public function enableMfaTotp(HttpRequestContext $request): HttpResultContext {
        $args = $request->route->getRequestArguments();
        $totp = $args['totp-code'];
        if(UserContext::Instance()->userCredentials->totp->verifyTotp($totp)) {
            UserContext::Instance()->userCredentials->totp->enableTotp();
            return new RedirectHttpResult('/dashboard/user/settings/mfa/totp');
        } else {
            return new ViewHttpResult('EnableUserMfaTotpView',
                [
                    'status' => EnableUserMfaTotpView::StatusInvalidTotp
                ]);
        }
    }

    public function disableMfaTotp(HttpRequestContext $request): HttpResultContext {
        $args = $request->route->getRequestArguments();
        $totp = $args['totp-code'];
        $renewSecrets = $args['renew-secrets'];
        Log::Object(__FILE__, $renewSecrets);
        if(UserContext::Instance()->userCredentials->totp->verifyTotp($totp)) {
            UserContext::Instance()->userCredentials->totp->disableTotp($renewSecrets == Defaults::YES);
            return new RedirectHttpResult('/dashboard/user/settings/mfa/totp');
        } else {
            return new ViewHttpResult('DisableUserMfaTotpView',
                [
                    'status' => EnableUserMfaTotpView::StatusInvalidTotp
                ]);
        }
    }

    public function recoverMfaTotp(HttpRequestContext $request): HttpResultContext {

        $username = $request->route->getRequestArguments()['username'];
        $recoveryKey = $request->route->getRequestArguments()['recovery-key'];

        $user = UserModel::GetByUsernameOrEmail($username);
        $userCredentials = new UserCredentials($user);
        if($userCredentials->totp->mfaTotpEnabled()) {
            if($userCredentials->totp->verifyRecoveryKey($recoveryKey)) {
                $userCredentials->totp->disableTotp(true);
                return new ViewHttpResult('RecoverUserMfaTotpView',
                    [
                        'status' => RecoverUserMfaTotpView::StatusMfaDisabled
                    ]);
            }
        }
        return new ViewHttpResult('RecoverUserMfaTotpView',
            [
                'status' => RecoverUserMfaTotpView::StatusInvalidRecoveryKey
            ]);
    }
}
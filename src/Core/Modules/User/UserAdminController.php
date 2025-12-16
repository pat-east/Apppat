<?php

class UserAdminController extends Controller {

    public const string NONCE_SECRET = 'user-admin-nonce-secret-69Laxc2zIY';

    public function __construct(Core $core) {
        parent::__construct($core);

        $this->registerRoute(new RegexCtrlRoute('#^/dashboard/user/(?P<useruid>[^/]+)/?$#', [$this, 'updateUser'], [
            'arg' => 'value'
        ], self::NONCE_SECRET));
    }

    public function updateUser(HttpRequestContext $request): HttpResultContext {
        $userUid = $request->route->getRequestArguments()['useruid'];
        $user = UserModel::GetByUid($userUid);
        if(!$user) {
            return new RedirectHttpResult('/dashboard/user/');
        } else {
            $new_roles = array_filter(array_keys($request->route->getRequestArguments()), function ($args) {
                return str_starts_with($args, 'userrole-') ?? false;
            });
            $new_roles = array_map(function ($arg) {
                return str_replace('userrole-','', $arg);
            }, $new_roles);
            $new_roles = implode(';', $new_roles);
            $user->update('roles', $new_roles);
            $request->route->args['username'] = $user->username;
            return new ViewHttpResult('UserView',
                [

                ]);
        }

    }
}
<?php

class UserController extends Controller
{
    public function __construct(Core $core)
    {
        parent::__construct($core);

        $this->registerRoute(new ViewRoute('/user', 'UsersView'));
        $this->registerRoute(new ViewRoute('/user/register', 'UserRegisterView'));
        $this->registerRoute(new RegexViewRoute('#^/user/(?P<username>[^/]+)/?$#', 'UserView'));
    }
}
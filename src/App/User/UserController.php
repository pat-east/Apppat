<?php

class UserController extends Controller {
    public function __construct(Core $core) {
        parent::__construct($core);

        $this->registerRoute(new Route('/users', 'UsersView'));
        $this->registerRoute(new RegexRoute('#^/users/(?P<username>[^/]+)/?$#', 'UserView'));
    }
}
<?php

class UsersDashboardItem extends DashboardItem {
    public function __construct() {
        parent::__construct(
            'Users',
            'See all registered users.',
            'User settings',
            'users',
            '/dashboard/users',
            'UsersView'
        );
    }
}
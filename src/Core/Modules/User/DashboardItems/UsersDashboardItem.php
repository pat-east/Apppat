<?php

class UsersDashboardItem extends DashboardItem {
    public function __construct() {
        parent::__construct(
            'Users and roles',
            'See all registered users and roles.',
            'User settings',
            'users',
            '/dashboard/user',
            'UsersView'
        );
    }

    protected function getRequiredUserPrivileges(): array {
        return [ AdminPrivilege::class ];
    }
}
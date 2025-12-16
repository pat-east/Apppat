<?php

class ChangeUserPasswordDashboardItem extends DashboardItem {
    public function __construct() {
        parent::__construct(
            'Change password',
            'Change your current password.',
            'User settings',
            'settings',
            '/dashboard/user/settings/password',
            'ChangeUserPasswordView',
            [ new NonePrivilege() ]
        );
    }

    protected function getRequiredUserPrivileges(): array {
        return [ UserProfilePrivilege::class ];
    }
}
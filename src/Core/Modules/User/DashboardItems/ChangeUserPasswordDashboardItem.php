<?php

class ChangeUserPasswordDashboardItem extends DashboardItem {
    public function __construct() {
        parent::__construct(
            'Change password',
            'Change your current password.',
            'User settings',
            'settings',
            '/dashboard/user/settings/password',
            'ChangeUserPasswordView'
        );
    }
}
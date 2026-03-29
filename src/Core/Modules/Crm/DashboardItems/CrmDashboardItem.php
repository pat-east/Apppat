<?php

class CrmDashboardItem extends DashboardItem {

    public function __construct() {
        parent::__construct(
            'Address and contact information',
            'Manage your personal data, address and contact information',
            'User settings',
            'info',
            '/dashboard/user/crm/manage',
            'CrmView'
        );
    }

    protected function getRequiredUserPrivileges(): array {
        return [ UserProfilePrivilege::class ];
    }
}
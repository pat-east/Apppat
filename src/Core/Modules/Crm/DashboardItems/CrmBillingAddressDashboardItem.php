<?php

class CrmBillingAddressDashboardItem extends DashboardItem {

    public function __construct() {
        parent::__construct(
            'Billing and shipping address',
            'Manage your billing and shipping addresses',
            'User settings',
            'bookmark',
            '/dashboard/user/crm/billing/manage',
            'CrmManageBillingAndShippingAddressesView'
        );
    }

    protected function getRequiredUserPrivileges(): array {
        return [ UserProfilePrivilege::class ];
    }
}
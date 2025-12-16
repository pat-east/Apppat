<?php

class ContactsDashboardItem extends DashboardItem {
    public function __construct() {
        parent::__construct(
            'Inbox',
            'Here you can see your inbox.',
            'Contacts',
            'comments',
            '/dashboard/inbox',
            'ContactInboxView',
            [ new AdminPrivilege() ]
        );
    }

    protected function getRequiredUserPrivileges(): array {
        return [ ManageContactInboxPrivilege::class ];
    }
}
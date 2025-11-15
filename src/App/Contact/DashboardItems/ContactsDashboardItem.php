<?php

class ContactsDashboardItem extends DashboardItem {
    public function __construct() {
        parent::__construct(
            'Inbox',
            'Here you can see your inbox.',
            'Contacts',
            'comments',
            '/dashboard/inbox',
            'ContactInboxView'
        );
    }
}
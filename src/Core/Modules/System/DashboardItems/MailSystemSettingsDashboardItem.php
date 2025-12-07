<?php

class MailSystemSettingsDashboardItem extends DashboardItem {
    public function __construct() {
        parent::__construct(
            'Mail System Settings',
            'See mail system settings and send test-mails.',
            'Mail settings',
            'mail',
            '/dashboard/system/settings/mail',
            'MailSystemSettingsView'
        );
    }
}
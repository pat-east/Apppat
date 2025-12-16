<?php

class AdminUserRole extends UserRole {
    public const string ROLE = 'admin';

    public function __construct() {
        parent::__construct(self::ROLE);

        $this->addPrivilege(new UserConfigPrivilege());
        $this->addPrivilege(new EditUsersPrivilege());
    }
}
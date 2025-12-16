<?php

class SysAdminUserRole extends UserRole {
    public const string ROLE = 'sysadmin';

    public function __construct() {
        parent::__construct(self::ROLE);

        $this->addPrivilege(new SysConfigPrivilege());
    }
}
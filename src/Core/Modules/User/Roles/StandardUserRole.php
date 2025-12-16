<?php

class StandardUserRole extends UserRole {
    public const string ROLE = 'user';

    public function __construct() {
        parent::__construct(self::ROLE);

        $this->addPrivilege(new UserProfilePrivilege());
    }
}
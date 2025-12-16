<?php

class ContributerUserRole extends UserRole {
    public const string ROLE = 'contributer';

    public function __construct() {
        parent::__construct(self::ROLE);

    }
}
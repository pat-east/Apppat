<?php

class EditorUserRole extends UserRole {
    public const string ROLE = 'editor';

    public function __construct() {
        parent::__construct(self::ROLE);

    }
}
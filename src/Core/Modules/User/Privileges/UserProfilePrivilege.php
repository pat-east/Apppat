<?php

class UserProfilePrivilege extends UserPrivilege {
    public function __construct() {
        parent::__construct('Enables basic functionality like editing profile settings.');
    }
}
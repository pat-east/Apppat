<?php

abstract class UserRole {

    private string $uid;

    /** @var UserPrivilege[] */
    private array $privileges;

    public function __construct(string $uid) {
        $this->uid = $uid;
        $this->privileges = [];
    }

    public function getUid(): string {
        return $this->uid;
    }

    /**
     * @return UserPrivilege[]
     */
    public function getPrivileges(): array {
        return $this->privileges;
    }

    /**
     * @return string[]
     */
    public function getPrivilegesUids(): array {
        return array_map(function($privilege) { return $privilege->uid; }, $this->privileges);
    }

    public function addPrivilege(UserPrivilege $privilege): void {
        $this->privileges[] = $privilege;
    }
}
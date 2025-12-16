<?php

abstract class UserPrivilege {
    private string $uid;
    private string $description;

    public function __construct(string $description = '') {
        $this->uid = get_class($this);
        $this->description = $description;
    }

    public function getUid():string {
        return $this->uid;
    }

    public function getDescription(): string {
        return $this->description;
    }
}
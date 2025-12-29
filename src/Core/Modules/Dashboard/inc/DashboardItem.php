<?php

abstract class DashboardItem {
    public string $title;
    public string $description;
    public string $category;
    public string $icon;
    public string $link;
    public string $viewClassName;

    /** @var string[] Array of class-names of UserPrivilege */
    private array $requiredUserPrivilegesUids = [];

    public function __construct(string $title, string $description,
                                string $category, string $icon,
                                string $link, string $viewClassName) {
        $this->title = $title;
        $this->description = $description;
        $this->category = $category;
        $this->icon = $icon;
        $this->link = $link;
        $this->viewClassName = $viewClassName;
        $this->requiredUserPrivilegesUids = $this->getRequiredPrivilegeUids();
    }

    public function getRequiredPrivilegeUids(): array {
        return $this->requiredUserPrivilegesUids;
    }

    public function init(): void {

    }

    /**
     * @param UserPrivilege[] $userPrivileges
     */
    public function isAccessable(array $userPrivileges): bool {

        $userPrivsUids = array_map(function($privilege) {
            return $privilege->getUid();
        }, $userPrivileges);

        if(in_array(new NonePrivilege()->getUid(), $this->requiredUserPrivilegesUids)) {
            return true;
        }

        if(count(array_diff($this->getRequiredPrivilegeUids(), $userPrivsUids)) == 0) {
            return true;
        }

        return false;
    }

    /**
     * @return string[] Returns array of class-names of UserPrivilege
     */
    protected abstract function getRequiredUserPrivileges(): array;

}
<?php

abstract class DashboardItem {
    public string $title;
    public string $description;
    public string $category;
    public string $icon;
    public string $link;
    public string $viewClassName;

    /** @var string[] Array of class-names of UserPrivilege */
    private array $requiredUserPrivileges = [];

    /**
     * @param UserPrivilege[] $requiredUserPrivileges
     */
    public function __construct(string $title, string $description,
                                string $category, string $icon,
                                string $link, string $viewClassName,
                                array $requiredUserPrivileges) {
        $this->title = $title;
        $this->description = $description;
        $this->category = $category;
        $this->icon = $icon;
        $this->link = $link;
        $this->viewClassName = $viewClassName;
        $this->requiredUserPrivileges = $requiredUserPrivileges;
    }

    public function getRequiredPrivilegeUids(): array {
        return array_map(function($privilege) { return $privilege->getUid(); }, $this->requiredUserPrivileges);
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

        if(in_array(new NonePrivilege()->getUid(), $userPrivsUids)) {
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
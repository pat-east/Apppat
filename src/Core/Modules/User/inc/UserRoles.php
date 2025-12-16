<?php

class UserRoles {

    /** @return UserRole[] */
    public static function GetAllRoles() {
        return array_map(function(string $role) {
            return new $role();
        }, Helper::GetDerivingClasses('UserRole'));
    }

    const ROLE_DELIMETER = ';';

    var UserModel $user;

    public function __construct(UserModel $user) {
        $this->user = $user;
    }

    public function hasRole($role): bool {
        return in_array($role, $this->getRolesUids());
    }

    /**
     * @return UserRole[]
     */
    public function getRoles(): array {
        $roleClasses = self::GetAllRoles();
        $roleUids = $this->getRolesUids();
        /** @var UserRole[] $userRoles */
        $userRoles = [];

        foreach($roleUids as $roleUid) {
            $instances = array_filter($roleClasses, function($instance) use ($roleUid) {
                return $instance->getUid() === $roleUid;
            });
            if(count($instances) > 0) {
                $userRoles[] = array_shift($instances);
            }
        }
        return $userRoles;
    }

    public function getRolesUids(): array {
        return explode(self::ROLE_DELIMETER, $this->user->roles);
    }

    /**
     * @return UserPrivilege[]
     */
    public function getPrivileges(): array {
        $privs = [];
        foreach($this->getRoles() as $role) {
            foreach($role->getPrivileges() as $priv) {
                $privs[] = $priv;
            }
        }
        return $privs;
    }

    /**
     * @return class-string[]
     */
    public function getPrivilegesUids(): array {
        $privs = $this->getPrivileges();
        $privUids = [];
        foreach($privs as $priv) {
            $privUids[] = $priv->getUid();
        }
        return $privUids;
    }
}
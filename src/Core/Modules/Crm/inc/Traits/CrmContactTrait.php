<?php

trait CrmContactTrait {
    var ?string $contactId = null;
    var ?string $entityId = null;
    var ?string $contactDisplayName = null;
    var ?string $phone = null;
    var ?string $mobile = null;
    var ?string $email = null;
    var ?string $www = null;
    var ?string $fax = null;

    /**
     * @param CrmContactTrait $usesCrmBaseEntityTrait
     */
    public function adoptCrmContactTrait(mixed $usesCrmBaseEntityTrait): void {
        if(!$usesCrmBaseEntityTrait) { return; }
        $this->contactId = $usesCrmBaseEntityTrait->contactId;
        $this->entityId = $usesCrmBaseEntityTrait->entityId;
        $this->contactDisplayName = $usesCrmBaseEntityTrait->contactDisplayName;
        $this->phone = $usesCrmBaseEntityTrait->phone;
        $this->mobile = $usesCrmBaseEntityTrait->mobile;
        $this->email = $usesCrmBaseEntityTrait->email;
        $this->www = $usesCrmBaseEntityTrait->www;
        $this->fax = $usesCrmBaseEntityTrait->fax;
    }
}
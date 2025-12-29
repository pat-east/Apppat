<?php

trait CrmAddressTrait {
    var ?string $addressId = null;
    var ?string $entityId = null;
    var ?string $addressDisplayName = null;
    var ?string $street = null;
    var ?string $street_hno = null;
    var ?string $city = null;
    var ?string $zip = null;
    var ?string $state = null;
    var ?string $region = null;
    var ?string $country = null;

    /**
     * @param CrmContactTrait $usesCrmBaseEntityTrait
     */
    public function adoptCrmAddressTrait(mixed $usesCrmAddressTrait): void {
        if(!$usesCrmAddressTrait) { return; }
        $this->addressId = $usesCrmAddressTrait->addressId;
        $this->entityId = $usesCrmAddressTrait->entityId;
        $this->addressDisplayName = $usesCrmAddressTrait->addressDisplayName;
        $this->street = $usesCrmAddressTrait->street;
        $this->street_hno = $usesCrmAddressTrait->street_hno;
        $this->city = $usesCrmAddressTrait->city;
        $this->zip = $usesCrmAddressTrait->zip;
        $this->state = $usesCrmAddressTrait->state;
        $this->region = $usesCrmAddressTrait->region;
        $this->country = $usesCrmAddressTrait->country;
    }
}
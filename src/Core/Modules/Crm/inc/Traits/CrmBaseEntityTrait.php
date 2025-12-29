<?php

trait CrmBaseEntityTrait {
    var ?string $id = null;
    var ?string $userUid = null;
    var ?string $typeUid = null;
    var ?string $firstname = null;
    var ?string $name = null;
    var ?string $title = null;
    var ?string $salutation = null;
    var ?DateTime $birthdate = null;
    var ?string $vatId = null;

    /**
     * @param CrmBaseEntityTrait $usesCrmBaseEntityTrait
     */
    public function adoptCrmBaseEntityTrait(mixed $usesCrmBaseEntityTrait): void {
        $this->id = $usesCrmBaseEntityTrait->id;
        $this->userUid = $usesCrmBaseEntityTrait->userUid;
        $this->typeUid = $usesCrmBaseEntityTrait->typeUid;
        $this->firstname = $usesCrmBaseEntityTrait->firstname;
        $this->name = $usesCrmBaseEntityTrait->name;
        $this->title = $usesCrmBaseEntityTrait->title;
        $this->salutation = $usesCrmBaseEntityTrait->salutation;
        $this->birthdate = $usesCrmBaseEntityTrait->birthdate;
        $this->vatId = $usesCrmBaseEntityTrait->vatId;

    }
}

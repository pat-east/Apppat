<?php

Helper::IncludeOnce(__DIR__ . '/Traits/');

class CrmEntity {
    use CrmBaseEntityTrait, CrmContactTrait, CrmAddressTrait;

    public function __construct(?CrmEntityModel $entity = null) {
        if($entity) {
            $this->adoptCrmBaseEntityTrait($entity);
            $this->adoptCrmContactTrait($entity);
            $this->adoptCrmAddressTrait($entity);
        }
    }

    public function getDisplayName(): string {
        return trim("{$this->title} {$this->firstname} {$this->name}");
    }
}




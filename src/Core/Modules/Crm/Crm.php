<?php

class Crm {

    var UserModel $user;

    /** @var CrmEntity[] */
    var array $entities;

    var CrmEntity $commonEntity {
        get {
            return $this->commonEntity;
        }
    }

    var CrmEntity $billingAddressEntity {
        get {
            return $this->billingAddressEntity;
        }
    }

    var CrmEntity $shippingAddressEntity {
        get {
            return $this->shippingAddressEntity;
        }
    }

    public function __construct(UserModel $user) {
        $this->user = $user;
        $this->reloadEntity();

        EventBus::RegisterEvent(new Event($this, 'crm-entity-created', [ 'entity' => CrmEntity::class ]));
        EventBus::RegisterEvent(new Event($this, 'crm-entity-updated', [ 'entity' => CrmEntity::class ]));

        EventBus::On(new EventHandler('crm-entity-created', [ $this, 'reloadEntity' ]));
    }

    public function createCommonEntity(CrmEntity $entity): CrmEntity {
        return $this->createEntity($entity);
    }

    public function createEntity(CrmEntity $entity): CrmEntity {
        $e = new CrmEntity(CrmEntityModel::Create($entity));

        EventBus::Raise(new Event($this, 'crm-entity-created', [ 'entity' => $e ]));
        return $e;
    }

    public function updateCommonEntity(CrmEntity $entity): CrmEntity {
        return $this->updateEntity($entity);
    }

    public function updateEntity(CrmEntity $entity): CrmEntity {
        $e = new CrmEntity(CrmEntityModel::Update($entity));
        EventBus::Raise(new Event($this, 'crm-entity-updated', [ 'entity' => $e ]));
        return $e;
    }

    public function getEntityByType(string $crmType): CrmEntity {
        return array_find($this->entities, static function (CrmEntity $entity) use ($crmType) {
            return $entity->typeUid == $crmType;
        }) ?? $this->createEmptyEntity($crmType);
    }

    public function reloadEntity(): void {
        $this->entities = [];
        $this->commonEntity = $this->createEmptyEntity(new CrmCommonType()->getType());
        $this->billingAddressEntity = $this->createEmptyEntity(new CrmDebtorType()->getType());
        $this->shippingAddressEntity = $this->createEmptyEntity(new CrmCreditorType()->getType());
        $entityModels = CrmEntityModel::GetByUserUid($this->user->uid);
        if($entityModels) {
            $this->entities = array_map(function($em) { return new CrmEntity($em); }, $entityModels);
            $this->commonEntity = $this->getEntityByType(new CrmCommonType()->getType());
            $this->billingAddressEntity = $this->getEntityByType(new CrmDebtorType()->getType());
            $this->shippingAddressEntity = $this->getEntityByType(new CrmCreditorType()->getType());
        }
    }

    private function createEmptyEntity(string $crmType): CrmEntity {
        $entity = new CrmEntity();
        $entity->userUid = $this->user->uid;
        $entity->typeUid = $crmType;
        return $entity;
    }
}

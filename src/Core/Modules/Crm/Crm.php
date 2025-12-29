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

    public function __construct(UserModel $user) {
        $this->user = $user;
        $this->reloadEntity();

        EventBus::RegisterEvent(new Event($this, 'crm-entity-created', [ 'entity' => CrmEntity::class ]));
        EventBus::RegisterEvent(new Event($this, 'crm-entity-updated', [ 'entity' => CrmEntity::class ]));

        EventBus::On(new EventHandler('crm-entity-created', [ $this, 'reloadEntity' ]));
    }

    public function createCommonEntity(CrmEntity $entity): CrmEntity {
        $e = new CrmEntity(CrmEntityModel::Create($entity));

        EventBus::Raise(new Event($this, 'crm-entity-created', [ 'entity' => $e ]));
        return $e;
    }

    public function updateCommonEntity(CrmEntity $entity): CrmEntity {
        $e = new CrmEntity(CrmEntityModel::Update($entity));
        EventBus::Raise(new Event($this, 'crm-entity-updated', [ 'entity' => $e ]));
        return $e;
    }

    public function reloadEntity(): void {
        $this->entities = [];
        $this->commonEntity = new CrmEntity();
        $entityModels = CrmEntityModel::GetByUserUid($this->user->uid);
        if($entityModels) {
            $this->entities = array_map(function($em) { return new CrmEntity($em); }, $entityModels);
            $this->commonEntity = array_find($this->entities, static function (CrmEntity $entity) {
                return $entity->typeUid == new CrmCommonType()->getType();
            });
        }
    }
}
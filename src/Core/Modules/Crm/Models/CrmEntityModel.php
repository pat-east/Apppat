<?php

include_once('CrmBaseModel.php');
include_once('CrmContactModel.php');
include_once('CrmAddressModel.php');

class CrmEntityModel {

    use CrmBaseEntityTrait, CrmAddressTrait, CrmContactTrait;

    public static function Create($entity): CrmEntityModel {
        $resultingEntity = new CrmEntityModel();
        $entity->adoptCrmBaseEntityTrait(CrmBaseModel::Create($entity));
        $entity->entityId = $entity->id;
        $entity->adoptCrmContactTrait(CrmContactModel::Create($entity));
        $entity->adoptCrmAddressTrait(CrmAddressModel::Create($entity));
        return $resultingEntity;
    }

    public static function Update($entity): CrmEntityModel {
        if(!$entity->id) {
            return self::Create($entity);
        }
        $resultingEntity = new CrmEntityModel();
        $entity->adoptCrmBaseEntityTrait(CrmBaseModel::Update($entity));
        $entity->adoptCrmContactTrait(CrmContactModel::Update($entity));
        $entity->adoptCrmAddressTrait(CrmAddressModel::Update($entity));
        return $resultingEntity;
    }

    /**
     * @return CrmEntityModel|CrmEntityModel[]|null
     */
    public static function Get(string $id, ?string $crmType = null, bool $single = false): CrmEntityModel|array|null {
        $em = CrmBaseModel::Get($id, $crmType, $single);
        if($single) {
            $e = new self();
            $e->adoptCrmBaseEntityTrait($em);
            $e->adoptCrmContactTrait(CrmContactModel::GetByEntityId($em->id, true));
            $e->adoptCrmAddressTrait(CrmAddressModel::GetByEntityId($em->id, true));
            return $e;
        } else {
            $es = [];
            foreach($em as $entity) {
                $e = new self();
                $e->adoptCrmBaseEntityTrait($entity);
                $e->adoptCrmContactTrait(CrmContactModel::GetByEntityId($em->id, true));
                $e->adoptCrmAddressTrait(CrmAddressModel::GetByEntityId($em->id, true));
                $es[] = $e;
            }
            return $es;
        }
    }

    /**
     * @return CrmEntityModel[]|null
     */
    public static function GetByUserUid(string $userUid, ?string $crmType = null): array|null {
        $em = CrmBaseModel::GetByUserUid($userUid, $crmType);
        if(!$em) {
//            return self::Get(array_shift($em)->id, $crmType);
            return null;
        }
        $es = [];
        foreach($em as $entity) {
            $es[] = self::Get($entity->id, null, true);
        }
        return $es;
    }
}
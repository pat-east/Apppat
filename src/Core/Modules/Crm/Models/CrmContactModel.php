<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CrmContactModel extends DatabaseModel {

    use CrmContactTrait;

    const string TableName = 'crm_contact';

    public static function Create(CrmEntity $entity): CrmContactModel {
        $id = Capsule::table(self::TableName)->insertGetId([
            'entity_id' => $entity->entityId,
            'display_name' => $entity->contactDisplayName,
            'phone' => $entity->phone,
            'mobile' => $entity->mobile,
            'email' => $entity->email,
            'www' => $entity->www,
            'fax' => $entity->fax,
            'created_at' => new DateTime(),
        ]);
        return self::Get($id, true);
    }

    public static function Update(CrmEntity $entity): CrmContactModel {
        Capsule::table(self::TableName)
            ->where('id', $entity->id)
            ->where('entity_id', $entity->entityId)
            ->update([
                'id' => $entity->contactId,
                'entity_id' => $entity->entityId,
                'display_name' => $entity->contactDisplayName,
                'phone' => $entity->phone,
                'mobile' => $entity->mobile,
                'email' => $entity->email,
                'www' => $entity->www,
                'fax' => $entity->fax,
                'updated_at' => new DateTime(),

            ]);
        return self::Get($entity->contactId, true);
    }

    /**
     * @return CrmContactModel|CrmContactModel[]|null
     */
    public static function Get(string $id, bool $single = false): CrmContactModel|array|null {
        $query = Capsule::table(self::TableName)
            ->where('id', $id);
        if($single) {
            $query = $query->limit(1);
        }
        $entities = $query->get()->toArray();
        if(count($entities) > 0) {
            if($single) {
                return new CrmContactModel(array_shift($entities));
            }
            return array_map(function($entity) { return new CrmContactModel($entity); }, $entities);

        }
        return null;
    }

    /**
     * @return CrmContactModel|CrmContactModel[]|null
     */
    public static function GetByEntityId(string $entityId, bool $single = false): CrmContactModel|array|null {
        $query = Capsule::table(self::TableName)
            ->where('entity_id', $entityId);
        if($single) {
            $query = $query->limit(1);
        }
        $entities = $query->get()->toArray();
        if(count($entities) > 0) {
            if($single) {
                return new CrmContactModel(array_shift($entities));
            }
            return array_map(function($entity) { return new CrmContactModel($entity); }, $entities);

        }
        return null;
    }

    public function __construct($entity = null) {
        parent::__construct(self::TableName);

        if($entity) {
            $this->contactId = $entity->id;
            $this->entityId = $entity->entity_id;
            $this->contactDisplayName = $entity->display_name;
            $this->phone = $entity->phone;
            $this->mobile = $entity->mobile;
            $this->email = $entity->email;
            $this->www = $entity->www;
            $this->fax = $entity->fax;
        }
    }

    protected function createTable(): void {
        Capsule::schema()->create(self::TableName, function ($table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();
            $table->bigInteger('entity_id')->unsigned();

            $table->string('display_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('www')->nullable();
            $table->string('fax')->nullable();

            $table->timestamps();
            $table->primary([ 'id', 'entity_id' ]);
        });
    }
}
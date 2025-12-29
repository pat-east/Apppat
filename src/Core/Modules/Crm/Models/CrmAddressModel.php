<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CrmAddressModel extends DatabaseModel {

    use CrmAddressTrait;

    const string TableName = 'crm_address';

    public static function Create(CrmEntity $entity): CrmAddressModel {
        $id = Capsule::table(self::TableName)->insertGetId([
            'entity_id' => $entity->entityId,
            'display_name' => $entity->contactDisplayName,
            'street' => $entity->street,
            'street_hno' => $entity->street_hno,
            'zip' => $entity->zip,
            'city' => $entity->city,
            'state' => $entity->state,
            'region' => $entity->region,
            'country' => $entity->country,
            'created_at' => new DateTime(),
        ]);
        return self::Get($id, true);
    }

    public static function Update(CrmEntity $entity): CrmAddressModel {
        Capsule::table(self::TableName)
            ->where('id', $entity->id)
            ->where('entity_id', $entity->entityId)
            ->update([
                'id' => $entity->addressId,
                'entity_id' => $entity->entityId,
                'display_name' => $entity->contactDisplayName,
                'street' => $entity->street,
                'street_hno' => $entity->street_hno,
                'zip' => $entity->zip,
                'city' => $entity->city,
                'state' => $entity->state,
                'region' => $entity->region,
                'country' => $entity->country,
                'updated_at' => new DateTime(),

            ]);
        return self::Get($entity->contactId, true);
    }

    /**
     * @return CrmAddressModel|CrmAddressModel[]|null
     */
    public static function Get(string $id, bool $single = false): CrmAddressModel|array|null {
        $query = Capsule::table(self::TableName)
            ->where('id', $id);
        if($single) {
            $query = $query->limit(1);
        }
        $entities = $query->get()->toArray();
        if(count($entities) > 0) {
            if($single) {
                return new CrmAddressModel(array_shift($entities));
            }
            return array_map(function($entity) { return new CrmAddressModel($entity); }, $entities);

        }
        return null;
    }

    /**
     * @return CrmAddressModel|CrmAddressModel[]|null
     */
    public static function GetByEntityId(string $entityId, bool $single = false): CrmAddressModel|array|null {
        $query = Capsule::table(self::TableName)
            ->where('entity_id', $entityId);
        if($single) {
            $query = $query->limit(1);
        }
        $entities = $query->get()->toArray();
        if(count($entities) > 0) {
            if($single) {
                return new CrmAddressModel(array_shift($entities));
            }
            return array_map(function($entity) { return new CrmAddressModel($entity); }, $entities);

        }
        return null;
    }

    public function __construct($entity = null) {
        parent::__construct(self::TableName);

        if($entity) {
            $this->addressId = $entity->id;
            $this->entityId = $entity->entity_id;
            $this->addressDisplayName = $entity->display_name;
            $this->street = $entity->street;
            $this->street_hno = $entity->street_hno;
            $this->zip = $entity->zip;
            $this->city = $entity->city;
            $this->state = $entity->state;
            $this->region = $entity->region;
            $this->country = $entity->country;
        }
    }

    protected function createTable(): void {
        Capsule::schema()->create(self::TableName, function ($table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();
            $table->bigInteger('entity_id')->unsigned();

            $table->string('display_name')->nullable();
            $table->string('street')->nullable();
            $table->string('street_hno')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();

            $table->timestamps();
            $table->primary([ 'id', 'entity_id' ]);
        });
    }
}
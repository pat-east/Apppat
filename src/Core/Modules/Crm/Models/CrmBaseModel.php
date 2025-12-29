<?php

Helper::IncludeOnce(__DIR__ . '/../inc/Traits');

use Illuminate\Database\Capsule\Manager as Capsule;

class CrmBaseModel extends DatabaseModel {

    use CrmBaseEntityTrait;

    const string TableName = 'crm_entity';

    public static function Create(CrmEntity $entity): CrmBaseModel {

        $id = Capsule::table(self::TableName)->insertGetId([
            'type' => $entity->typeUid,
            'firstname' => $entity->firstname,
            'name' => $entity->name,
            'salutation' => $entity->salutation,
            'birthdate' => $entity->birthdate,
            'title' => $entity->title,
            'vat_id' => $entity->vatId,
            'user_uid' => $entity->userUid,
            'created_at' => new DateTime(),
        ]);
        return self::Get($id, null, true);

    }

    public static function Update(CrmEntity $entity): CrmBaseModel {
        Capsule::table(self::TableName)
            ->where('id', $entity->id)
            ->where('user_uid', $entity->userUid)
            ->update([
                'id' => $entity->id,
                'type' => $entity->typeUid,
                'firstname' => $entity->firstname,
                'name' => $entity->name,
                'salutation' => $entity->salutation,
                'birthdate' => $entity->birthdate,
                'title' => $entity->title,
                'vat_id' => $entity->vatId,
                'user_uid' => $entity->userUid,
                'updated_at' => new DateTime(),

            ]);
        return self::Get($entity->id, null, true);
    }

    /**
     * @return CrmBaseModel|CrmBaseModel[]|null
     */
    public static function Get(string $id, ?string $crmType = null, bool $single = false): CrmBaseModel|array|null {
        $query = Capsule::table(self::TableName)
            ->where('id', $id);
        if($crmType) {
            $query = $query->where('type', $crmType);
        }
        if($single) {
            $query = $query->limit(1);
        }
        $entities = $query->get()->toArray();
        if(count($entities) > 0) {
            if($single) {
                return new CrmBaseModel(array_shift($entities));
            }
            return array_map(function($entity) { return new CrmBaseModel($entity); }, $entities);

        }
        return null;
    }

    /**
     * @return CrmBaseModel[]|null
     */
    public static function GetByUserUid(string $userUid, ?string $crmType = null): array|null {
        $query = Capsule::table(self::TableName)
            ->where('user_uid', $userUid);
        if($crmType) {
            $query = $query->where('type', $crmType);
        }
        $entities = $query->get()->toArray();
        if(count($entities) > 0) {
            return array_map(function($entity) { return new CrmBaseModel($entity); }, $entities);
        }
        return null;
    }

    public function __construct($entity = null) {
        parent::__construct(self::TableName);

        if($entity) {
            $this->id = $entity->id;

            $this->typeUid = $entity->type;
            $this->firstname = $entity->firstname;
            $this->name = $entity->name;
            $this->title = $entity->title;
            $this->birthdate = $entity->birthdate ? new DateTime($entity->birthdate) : null;
            $this->salutation = $entity->salutation;
            $this->vatId = $entity->vat_id;

            $this->userUid = $entity->user_uid;
        }
    }

    protected function createTable(): void {
        Capsule::schema()->create(self::TableName, function ($table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();

            $table->string('type')->nullable();
            $table->string('firstname')->nullable();
            $table->string('name')->nullable();
            $table->string('salutation')->nullable();
            $table->string('title')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('vat_id')->nullable();

            $table->string('user_uid', 36)->nullable();

            $table->timestamps();
            $table->primary('id');
        });
    }
}
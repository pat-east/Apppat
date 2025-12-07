<?php

use Illuminate\Database\Capsule\Manager as Capsule;

abstract class DatabaseModel extends Model {

    var string $tableName;

    var $schema;

    public function __construct($table) {
        parent::__construct();

        $this->tableName = $table;

        $this->_createTableIfNotExists();
    }

    protected abstract function createTable() : void;

    private function _createTableIfNotExists() {
        $schema_builder = Capsule::connection()->getSchemaBuilder();
        if(!$schema_builder->hasTable($this->tableName)) {
            Log::Info(__FILE__, "Table {$this->tableName} does not yet. Creating it now.");
            $this->createTable();
        }
    }
}
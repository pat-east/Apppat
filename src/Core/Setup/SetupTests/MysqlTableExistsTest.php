<?php

class MysqlTableExistsTest extends SetupTest {

    var string $tableName;

    public function __construct(string $tableName) {
        parent::__construct('Checks existence of table ',
            'Checks existence of MySQL table: ' . $tableName,
            'Table exists',
            'Table does not exist. This table is mandatory and needs to be created.',
            function () use ($tableName) {
                $rows = MySqlClient::QueryRaw(
                    sprintf("SELECT * FROM %s%s LIMIT 1", Config::$MySql->tablePrefix, $tableName));
                return $rows != false;
            });
    }
}
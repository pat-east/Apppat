<?php

//use Illuminate\Events\Dispatcher;
//use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

include_once('Model.php');
include_once('DatabaseModel.php');
include_once('ModelRepository.php');



class ModelManager {
    public function __construct() {

    }

    public function init(): void {
        $capsule = new Capsule();
//        $capsule->setEventDispatcher(new Dispatcher(new Container()));
        $capsule->setAsGlobal();
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => Config::$MySql->host,
            'database' => Config::$MySql->database,
            'username' => Config::$MySql->username,
            'password' => Config::$MySql->password,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => Config::$MySql->tablePrefix,
        ]);
    }
}
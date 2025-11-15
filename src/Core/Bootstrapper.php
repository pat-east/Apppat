<?php

require_once(__DIR__ . '/../vendor/autoload.php');
require_once('inc/Http.php');
require_once('inc/Helper.php');
require_once('inc/Defaults.php');
require_once('inc/Config.php');
require_once('inc/Logger.php');
require_once('Core.php');

class Bootstrapper {

    public function __construct() {

    }

    public function boot(): Core {
        new Defaults()->init();
        new Config()->init();
        new Logger()->init();
        $core = new Core();
        $core->init();
        return $core;
    }

}
<?php

require_once('Core/inc/Helper.php');
require_once('Core/inc/Defaults.php');
require_once('Core/inc/Config.php');
require_once('Core/inc/Logger.php');
require_once('Core/Core.php');

class Bootstrapper {

    public function __construct() {

    }

    public function boot(): void {
        new Defaults()->init();
        new Config()->init();
        new Logger()->init();
        new Core()->init();

    }

}
<?php

require_once('Core/Bootstrapper.php');
require_once('Themes/UikitTheme/UikitTheme.php');

class App {

    var Core $core;

    public function __construct() {
        $bootstrapper = new Bootstrapper();
        $this->core = $bootstrapper->boot();
    }

    function init(): void {



        $this->core->themeManager->useTheme(new UikitTheme());
    }

    function run(): void {
        $this->core->run();
    }

}
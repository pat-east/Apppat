<?php

require_once('Core/Bootstrapper.php');

class App {

    var Core $core;

    function __construct() {
        $bootstrapper = new Bootstrapper();
        $this->core = $bootstrapper->boot();
    }

    function init(): void {
        $this->registerScriptsStyles();
    }

    function run(): void {
        $this->core->assetsManager->use('uikit');

        $this->core->run();
    }

    function registerScriptsStyles(): void {
        $this->core->assetsManager->registerScript(
            'jquery',
            '/public/assets/node_modules/jquery/dist/jquery.min.js');

        $this->core->assetsManager->registerScript(
            'bootstrap',
            '/public/assets/dist/bootstrap-4.1.3-dist/js/bootstrap.min.js',
            ['jquery'], '4.1.3');
        $this->core->assetsManager->registerStyle(
            'bootstrap',
            '/public/assets/dist/bootstrap-4.1.3-dist/css/bootstrap.min.css',
            [], '4.1.3');

        $this->core->assetsManager->registerScript(
            'bootstrap',
            '/public/assets/node_modules/bootstrap/dist/js/bootstrap.min.js');
        $this->core->assetsManager->registerStyle(
            'bootstrap',
            '/public/assets/node_modules/bootstrap/dist/css/bootstrap.min.css');

        $this->core->assetsManager->registerScript(
            'uikit',
            '/public/assets/node_modules/uikit/dist/js/uikit.min.js');
        $this->core->assetsManager->registerStyle(
            'uikit',
            '/public/assets/node_modules/uikit/dist/css/uikit.min.css');
    }

}
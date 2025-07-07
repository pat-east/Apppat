<?php

include_once('inc/Router.php');
include_once('inc/ErrorHandler.php');
include_once('inc/AssetManager.php');

class Core {

    static Core $Instance;
    var ErrorHandler $errorHandler;
    var Router $router;
    var AssetManager $assetsManager;

    function __construct() {
        if(isset(self::$Instance)) {
            Log::Info(__FILE__, "Core already initialized [version=%s]", Defaults::VERSION);
            return;
        }
        self::$Instance = $this;
        $this->errorHandler = new ErrorHandler();
        $this->router = new Router();
        $this->assetsManager = new AssetManager();
    }

    public function init(): void {
        $this->errorHandler->init();
        $this->router->init();
        $this->assetsManager->init();

//        Log::Info(__FILE__, "Core initialized [version=%s]", Defaults::VERSION);
    }

    public function run(): void {
        try {
            $this->router->route();
        } catch(Throwable $e) {
            $this->errorHandler->handleException($e);
        }
    }

}
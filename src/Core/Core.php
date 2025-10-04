<?php

include_once('inc/Router.php');
include_once('inc/ErrorHandler.php');
include_once('inc/AssetManager.php');
include_once('inc/ThemeManager.php');
include_once('inc/ModelManager.php');
include_once('inc/ControllerManager.php');

class Core {

    static Core $Instance;
    var ErrorHandler $errorHandler;
    var Router $router;
    var AssetManager $assetsManager;
    var ThemeManager $themeManager;
    var ModelManager $modelManager;
    var ControllerManager $controllerManager;

    public function __construct() {
        if(isset(self::$Instance)) {
            Log::Info(__FILE__, "Core already initialized [version=%s]", Defaults::VERSION);
            return;
        }
        self::$Instance = $this;
        $this->errorHandler = new ErrorHandler();
        $this->router = new Router();
        $this->assetsManager = new AssetManager();
        $this->themeManager = new ThemeManager();
        $this->modelManager = new ModelManager();
        $this->controllerManager = new ControllerManager();
    }

    public function init(): void {
        $this->errorHandler->init();
        $this->router->init();
        $this->assetsManager->init();
        $this->themeManager->init();

        /* All the app logic is inside /App-folder.
         * We now include all the files within that folder recursively. */
        Helper::IncludeOnce(Defaults::APPSPATH, true);

        // And now we initialize those ...
        $this->modelManager->init();
        $this->controllerManager->init($this);

//        Log::Info(__FILE__, "Core initialized [version=%s]", Defaults::VERSION);
    }

    public function run(): void {
        try {
            $route = $this->router->route();
            $view = new ($route->viewClassName)($route->getRequestArguments());
            if(!$this->themeManager->theme) {
                throw new Exception('Theme not set. Please define a theme using Core::themeManager->useTheme().');
            }
            $this->themeManager->theme->render($view);

        } catch(Throwable $e) {
            $this->errorHandler->handleException($e);
        }
    }

}
<?php

include_once('inc/Cors.php');
include_once('inc/Session.php');
include_once('inc/CsrfToken.php');
include_once('inc/Nonce.php');
include_once('inc/MySqlClient.php');
include_once('inc/Sanitize.php');
include_once('inc/Router.php');
include_once('inc/MiddlewareManager.php');
include_once('inc/ErrorHandler.php');
include_once('inc/AssetManager.php');
include_once('inc/ThemeManager.php');
include_once('inc/ModelManager.php');
include_once('inc/ControllerManager.php');
include_once('inc/ShortcodeFactory.php');
include_once('Setup/Setup.php');


class Core {

    static Core $Instance;
    var ErrorHandler $errorHandler;
    var Router $router;
    var AssetManager $assetsManager;
    var ThemeManager $themeManager;
    var ModelManager $modelManager;
    var ControllerManager $controllerManager;
    var Setup $setup;
    var MiddlewareManager $middlewareManager;

    public function __construct() {
        if (isset(self::$Instance)) {
            Log::Info(__FILE__, 'Core already initialized [version=%s]', Defaults::VERSION);
            return;
        }
        self::$Instance = $this;

        $this->errorHandler = new ErrorHandler();
        $this->router = new Router();
        $this->assetsManager = new AssetManager();
        $this->themeManager = new ThemeManager();
        $this->modelManager = new ModelManager();
        $this->middlewareManager = new MiddlewareManager();
        $this->controllerManager = new ControllerManager();
        $this->setup = new Setup($this);

    }

    public function init(): void {

        /* Session is singleton.
         * PHP-session must start before any client output (header, stdout etc.).
         */
        new Session()->init();

        $this->errorHandler->init();
        $this->router->init();
        $this->assetsManager->init();
        $this->themeManager->init();

        /* All build-in app logic is inside /Core/Modules-folder. */
        Helper::IncludeOnce(Defaults::ABSPATH . '/Core/Modules', true);
        /* All the app logic is inside /App-folder. */
        Helper::IncludeOnce(Defaults::ABSPATH . '/App/', true);

        // And now we initialize those ...
        $this->modelManager->init();
        $this->middlewareManager->init();
        $this->controllerManager->init($this);
        ShortcodeFactory::Init();
        $this->setup->init();

        // After all is set up and running, init the UserContext
        new UserContext();

//        Log::Info(__FILE__, "Core initialized [version=%s]", Defaults::VERSION);
    }

    public function run(): void {
        try {

            switch ($_SERVER['REQUEST_METHOD']) {
                // We only allow following request methods to be processed by apps
                case 'GET':
                    // Run setup if not already set up
                    if (!$this->setup->isSetUp()) {
                        $this->setup->runSetUp();
                    } else {
                        // If user accesses /setup-page and all tests pass, redirect the user to /
                        if ($_SERVER['REQUEST_URI'] == '/setup' && $this->setup->isSetUp()) {
                            header('Location: /');
                            exit;
                        }
                    }
                case 'POST':
                case 'PUT':
                case 'DELETE':
                    $route = $this->router->route();
                    $this->middlewareManager->run($route);
                    break;

                // OPTIONS request method is necessary to handle CORS
                case 'OPTIONS':
                    Cors::Headers();
                    exit(0);

                // Anything else gets answered by 405 - Method Not Allowed
                default:
                    http_response_code(405);
                    break;
            }


        } catch (Throwable $e) {
            $this->errorHandler->handleException($e);
        }
    }
}
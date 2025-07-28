<?php

require_once('Core/Bootstrapper.php');
require_once('Themes/UikitTheme/UikitTheme.php');

require_once('views/HomeView.php');

class App {

    var Core $core;

    public function __construct() {
        $bootstrapper = new Bootstrapper();
        $this->core = $bootstrapper->boot();
    }

    function init(): void {

        $this->core->router->registerRoute(new Route('/', HomeView::class));

        $this->core->themeManager->useTheme(new UikitTheme());
    }

    function run(): void {
        $this->core->run();
    }

}
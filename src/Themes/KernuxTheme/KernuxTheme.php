<?php

class KernuxTheme extends Theme {

    public function __construct() {
        parent::__construct();

        Core::$Instance->assetsManager->use('kernux');
    }

    public function render(View $view): void {
        $this->renderBreadcrumbs($view);
        $view->render();
    }
}

<?php

class SetupTheme extends Theme
{
    public function __construct() {
        parent::__construct();

        Core::$Instance->assetsManager->use('uikit');
    }

    public function render(View $view): void {
        // Trim whitespaces of each line before echoing the html
        ob_start();
        $this->renderLayout($view);
        $content = ob_get_clean();
        echo preg_replace('/^[ \t]+|[ \t]+$/m', '', $content);
    }

    function renderLayout(View $view): void {
        ?>
        <!DOCTYPE html>
        <html lang="de">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Apppat</title>
            <?php Core::$Instance->assetsManager->includeStyles(); ?>
        </head>
        <body>
            <main>
                <?php $this->renderContent($view); ?>
            </main>
            <?php Core::$Instance->assetsManager->includeScripts(); ?>
        </body>
        </html>
        <?php
    }

    function renderContent(View $view): void {
        ?>
        <?php $view->render(); ?>
        <?php
    }


}

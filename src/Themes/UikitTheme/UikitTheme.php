<?php

class UikitTheme extends Theme
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
            <?php $this->renderHeader(); ?>
            <main>
                <?php $this->renderContent($view); ?>
            </main>
            <?php $this->renderFooter(); ?>
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

    function renderHeader(): void {
        ?>
        <nav class="uk-navbar-container">
            <div class="uk-container">
                <div uk-navbar>
                    <div class="uk-navbar-center">
                        <div class="uk-navbar-center-left">
                            <ul class="uk-navbar-nav">
                                <li class="uk-active"><a href="/user">Users</a></li>
                            </ul>
                        </div>
                        <a class="uk-navbar-item uk-logo" href="/">
                            <span uk-icon="icon: heart; ratio: 1.5"></span>
                        </a>
                        <div class="uk-navbar-center-right">
                            <ul class="uk-navbar-nav">
                                <li><a href="/about">About</a></li>
                                <li><a href="/contact">Contact</a></li>
                                <li><a href="/contact/inbox">Inbox</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="uk-section uk-section-muted ">
            <div class="uk-container">
                <div class="uk-grid-match uk-child-width-1-2@m" uk-grid>
                    <div class="uk-text-center uk-flex-middle">
                        <p><span uk-icon="icon: image; ratio: 5"></span></p>
                    </div>
                    <div>
                        <p class="uk-text-lead">Welcome to <i><?= Defaults::APPTITLE ?></i></p>
                        <p class="uk-text-emphasis">This is a modern application framework written in PHP</p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    function  renderFooter(): void {
        ?>
        <div class="uk-section uk-section-secondary uk-light">
            <div class="uk-container">

                <h3>Section</h3>

                <div class="uk-grid-match uk-child-width-1-3@m" uk-grid>
                    <div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
                    </div>
                    <div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
                    </div>
                    <div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
                    </div>
                </div>

            </div>
        </div>
        <?php

    }
}

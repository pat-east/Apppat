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
                                <li><a href="/about">About</a></li>
                            </ul>
                        </div>
                        <a class="uk-navbar-item uk-logo" href="/">
                            <span uk-icon="icon: heart; ratio: 1.5"></span>
                        </a>
                        <div class="uk-navbar-center-right">
                            <ul class="uk-navbar-nav">
                                <li><a href="/contact">Contact</a></li>
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
        <?php $this->renderUserBar(); ?>
        <?php
    }

    function renderUserBar(): void {
        $userContext = UserContext::Instance();
        if($userContext->isUserLoggedIn) {
            $this->renderLoggedInBar();
        } else {
            $this->renderNotLoggedInBar();
        }
    }

    function renderNotLoggedInBar(): void {
        ?>
        <div class="uk-section uk-section-secondary uk-padding-remove-top uk-padding-remove-bottom">
            <div class="uk-container">
                <div uk-navbar>
                    <div class="uk-navbar-right">
                        <ul class="uk-navbar-nav">
                            <div class="uk-navbar-item">
                                <a href="/login" class="uk-button uk-button-default">
                                    <span uk-icon="sign-in"></span> Login
                                </a>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }

    function renderLoggedInBar(): void {
        $userContext = UserContext::Instance();
        ?>
        <div class="uk-section uk-section-secondary uk-padding-remove-top uk-padding-remove-bottom">
            <div class="uk-container">
                <div uk-navbar>
                    <div class="uk-navbar-left">
                        <ul class="uk-navbar-nav">
                            <li class="uk-active">
                                <a href="/dashboard"><span uk-icon="user"></span> Dashboard (<?= $userContext->user->username ?>)</a>
                            </li>
                        </ul>
                    </div>
                    <div class="uk-navbar-right">
                        <ul class="uk-navbar-nav">
                            <div class="uk-navbar-item">
                                <a href="/logout" class="uk-button uk-button-default">
                                    <span uk-icon="sign-out"></span> Logout
                                </a>
                            </div>
                        </ul>
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
                <div class="uk-child-width-1-3@m" uk-grid>
                    <div>
                        <h3>AppPat</h3>
                        <ul class="uk-list">
                            <li><a href="/about">About the Project</a></li>
                            <li><a href="#">Documentation</a></li>
                            <li><a href="#">Changelog</a></li>
                            <li><a href="/license">License</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3>Community</h3>
                        <ul class="uk-list">
                            <li><a href="#">Report a Vulnerability</a></li>
                            <li><a href="#">Community Portal</a></li>
                            <li><a href="#">How to Contribute</a></li>
                            <li><a href="#">Roadmap</a></li>
                            <li><a href="#">Issue Tracker</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3>Developer Resources</h3>
                        <ul class="uk-list">
                            <li>
                                <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers" target="_blank" rel="noopener">
                                    MDN: HTTP Security Headers
                                </a>
                            </li>
                            <li>
                                <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP" target="_blank" rel="noopener">
                                    MDN: Content Security Policy (CSP)
                                </a>
                            </li>
                            <li>
                                <a href="https://owasp.org/www-project-cheat-sheets/" target="_blank" rel="noopener">
                                    OWASP Cheat Sheets
                                </a>
                            </li>
                            <li>
                                <a href="https://owasp.org/www-project-developer-guide/" target="_blank" rel="noopener">
                                    OWASP Developer Guide
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        <?php

    }
}

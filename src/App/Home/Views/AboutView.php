<?php

class AboutView extends View {

    public function render(): void {
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h1>About <?= Defaults::APPTITLE ?></h1>
                <h2>About the Project</h2>
                <p>
                    Launched in 2024, this project delivers a security-focused application framework
                    that forms a robust foundation for modern web applications.
                </p>
                <ul class="uk-list uk-list-bullet">
                    <li>Built around <strong>security by design</strong></li>
                    <li>Framework that <strong>enforces secure coding practices</strong></li>
                    <li>Developers are guided to write secure, robust code</li>
                    <li>Provides a flexible base for various web applications</li>
                    <li>Fully developed in <strong>PHP</strong></li>
                </ul>

                <h2>What the Project Provides</h2>
                <p>
                    A lightweight yet powerful PHP framework with built-in security features for
                    building secure and scalable web applications.
                </p>
                <ul class="uk-list uk-list-bullet">
                    <li>Core framework for structured, maintainable web applications</li>
                    <li>
                        <strong>Controller-based routing</strong> with regular expression support
                        for clean, RESTful URLs without GET parameters
                    </li>
                    <li>Enforced <strong>nonces</strong> and <strong>CSRF tokens</strong></li>
                    <li>Built-in user login and registration</li>
                    <li>Built-in usage of HTTP security headers</li>
                    <li>
                        Native support for <strong>Content Security Policy (CSP)</strong> headers<br>
                        <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP" target="_blank" rel="noopener">
                            MDN Documentation on CSP
                        </a>
                    </li>
                    <li><strong>Two-Factor Authentication (2FA)</strong> with TOTP</li>
                    <li>Modular architecture for easy extension via modules</li>
                    <li>
                        Small footprint; optional dependencies only when needed
                        (e.g., ORM, PDF library)
                    </li>
                    <li>
                        ORM powered by <strong>Illuminate/Database</strong>
                        (as known from the Laravel framework)
                    </li>
                    <li><strong>100% PHP</strong> with modern PHP 8</li>
                    <li><strong>Strict typing</strong> for improved type safety</li>
                </ul>
            </div>
        </div>
        <?php
    }
}

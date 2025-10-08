<?php

class Status404View extends View {

    public function __construct() {
        parent::__construct([]);
    }

    public function render(): void {
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h1>404</h1>
                <p class="uk-text-lead">Page not found</p>
            </div>
        </div>
        <?php
    }
}
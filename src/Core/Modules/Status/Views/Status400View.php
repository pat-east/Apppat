<?php

class Status400View extends View {

    public function __construct()
    {
        parent::__construct([]);
    }

    public function render(): void
    {
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h1>400</h1>
                <p class="uk-text-lead">Bad request</p>
            </div>
        </div>
        <?php
    }
}
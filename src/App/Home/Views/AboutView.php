<?php

class AboutView extends View {

    public function render(): void {
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h1>About <?= Defaults::APPTITLE ?></h1>
            </div>
        </div>
        <?php
    }
}

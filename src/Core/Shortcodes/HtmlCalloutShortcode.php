<?php

class HtmlCalloutShortcode extends Shortcode {

    static function GetName(): string {
        return 'html-callout';
    }

    function process(): string {
        ob_start();
        ?>
        <div class="uk-alert-danger" uk-alert>
            <a href class="uk-alert-close" uk-close></a>
            <p><?= $this->attrs['message'] ?></p>
        </div>
        <?php
        return ob_get_clean();
    }
}
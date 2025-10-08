<?php

class HtmlPreShortcode extends Shortcode {

    static function GetName(): string {
        return 'html-pre';
    }

    function process(): string {
        ob_start();
        ?>
        <pre>
            <?= $this->content ?>
        </pre>
        <?php
        return ob_get_clean();
    }
}
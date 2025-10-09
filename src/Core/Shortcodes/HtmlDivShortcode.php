<?php

class HtmlDivShortcode extends Shortcode
{

    static function GetName(): string
    {
        return 'html-div';
    }

    function process(): string
    {
        ob_start();
        ?>
        <div>
            <?= $this->content ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
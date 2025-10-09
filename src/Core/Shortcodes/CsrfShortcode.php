<?php

class CSRFShortcode extends Shortcode
{

    static function GetName(): string
    {
        return 'form-csrf-token';
    }

    function process(): string
    {
        $csrf = CsrfToken::Create();
        ob_start();
        ?>
        <input type="hidden" name="<?= CsrfToken::CSRF_TOKEN_NAME ?>" value="<?= $csrf->token ?>"/>
        <?php
        return ob_get_clean();
    }
}
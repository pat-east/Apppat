<?php

class NonceShortcode extends Shortcode {

    static function GetName(): string {
        return 'form-nonce';
    }

    function process(): string {
        $nonce = Nonce::Create($this->attrs['secret']);
        ob_start();
        ?>
        <input type="hidden" value="<?= $nonce->nonce ?>" name="<?= Nonce::NONCE_NAME ?>" />
        <input type="hidden" value="<?= $nonce->payload ?>" name="<?= Nonce::NONCE_PAYLOAD ?>" />

        <?php
        return ob_get_clean();
    }
}
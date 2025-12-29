<?php

class FormHiddenInputShortcode extends Shortcode {

    static function GetName(): string {
        return 'form-hidden-input';
    }

    function process(): string {
        $random = random_int(11111, 99999);
        ob_start();
        ?>
        <input  type="hidden"
                value="<?= $this->attrs['value'] ?>"
                name="<?= $this->attrs['name'] ?>" />
        <?php
        return ob_get_clean();
    }

    function getDefaultArgs(): array {
        return [
            'name' => '',
            'value' => ''
        ];
    }
}
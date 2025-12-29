<?php

class FormSubmitShortcode extends Shortcode {

    static function GetName(): string {
        return 'form-submit';
    }

    function process(): string {
        ob_start();
        ?>
        <div class="uk-text-right">
            <p><button type="submit" class="uk-button uk-button-primary"><?= $this->attrs['label'] ?></button></p>
        </div>
        <?php
        return ob_get_clean();
    }

    function getDefaultArgs(): array {
        return [
            'label' => 'Submit'
        ];
    }
}
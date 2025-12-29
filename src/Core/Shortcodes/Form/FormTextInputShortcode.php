<?php

class FormTextInputShortcode extends Shortcode {

    static function GetName(): string {
        return 'form-text-input';
    }

    function process(): string {
        $random = random_int(11111, 99999);
        ob_start();
        ?>
        <div class="uk-margin">
            <?php if($this->attrs['label']) : ?>
                <label class="uk-form-label" for="form-input-<?= $random ?>"><?= $this->attrs['label'] ?></label>
            <?php endif; ?>
            <div class="uk-form-controls">
                <input  id="form-input-<?= $random ?>"
                        type="text"
                        value="<?= $this->attrs['value'] ?>"
                        name="<?= $this->attrs['name'] ?>"
                        placeholder="<?= $this->attrs['placeholder'] ?>"
                        class="uk-input" />
            </div>
        </div>


        <?php
        return ob_get_clean();
    }

    function getDefaultArgs(): array {
        return [
            'label' => '',
            'value' => '',
            'name' => '',
            'placeholder' => '',
        ];
    }
}
<?php

class FormShortcode extends Shortcode {

    static function GetName(): string {
        return 'form';
    }

    function process(): string {
        ob_start();
        ?>
        <form class="uk-form-horizontal uk-margin-large" action="<?= $this->attrs['action'] ?>" method="<?= $this->attrs['method'] ?>">
            <?php if($this->attrs['nonce']): ?>[form-nonce secret="<?= $this->attrs['nonce'] ?>"]<?php endif; ?>
            <?php if($this->attrs['csrf']): ?>[form-csrf]<?php endif; ?>
            <?= $this->content ?>
        </form>
        <?php
        return ob_get_clean();
    }

    function getDefaultArgs(): array {
        return [
            'action' => '',
            'method' => 'POST',
            'nonce' => null,
            'csrf' => false,
        ];
    }
}
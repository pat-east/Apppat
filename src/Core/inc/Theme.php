<?php

abstract class Theme {

    public function __construct() {

    }

    protected function renderBreadcrumbs(View $view): void {
        $items = $view->getBreadcrumbItems();
        if(count($items) === 0) {
            return;
        }
        ?>
        <div class="uk-section uk-section-xsmall">
            <div class="uk-container">
                <ul class="uk-breadcrumb uk-margin-remove">
                    <?php foreach($items as $item) : ?>
                        <li>
                            <?php if(isset($item['href']) && $item['href']) : ?>
                                <a href="<?= $item['href'] ?>"><?= $item['label'] ?></a>
                            <?php else : ?>
                                <span><?= $item['label'] ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php
    }

    abstract public function render(View $view): void;
}

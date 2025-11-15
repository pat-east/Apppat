<?php

class DashboardView extends View {

    public function render(): void {
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <div>
                    <?php foreach (Dashboard::Instance()->getDashboardItemCategories() as $categoryName => $items) : ?>
                        <h2><?= $categoryName ?></h2>
                        <div uk-grid uk-height-match="target: .uk-card; row: false">
                            <?php foreach ($items as $item) : ?>
                                <div class="dashboard-item uk-width-1-2@s uk-width-1-3@m">
                                    <a href="<?= $item->link ?>">
                                        <div class="uk-card uk-card-default uk-card-body ">
                                            <div uk-grid>
                                                <div class="uk-width-1-4">
                                                    <span uk-icon="icon: <?= $item->icon ?>; ratio: 2"></span>
                                                </div>
                                                <div class="uk-width-3-4">
                                                    <strong class="uk-display-block"><?= $item->title ?></strong>
                                                    <p><?= $item->description ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
}